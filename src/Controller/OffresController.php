<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Offres;
use App\Entity\Notifications;
use App\Entity\Etat;
use App\Entity\Statut;
use App\Entity\Categorie;
use App\Entity\FavoriteOffre;
use App\Form\OffresType;
use App\Form\modifType;
use App\Repository\OffresRepository;
use App\Entity\Users;
use App\Form\SearchType;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\FormError;
use App\Repository\NotificationsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\Query\Expr\Func;
use TCPDF;
use Twilio\Rest\Client;
use DateTime;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class OffresController extends AbstractController
{
  

    #[Route('/offres/dispo', name: 'showDispoOffres')]
    public function showDispoOffres(Request $request, OffresRepository $offresRepository,PaginatorInterface $paginator): Response
    {
        $now = new \DateTime();
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $data = $form->getData();
        $titre = isset($data['search']) ? $data['search'] : null;
        $categorie = isset($data['categorie']) ? $data['categorie'] : null; 
        $tri = $request->query->get('tri', 'dc');
        $qb = $offresRepository->createQueryBuilder('o');
    
        $tous = isset($data['tous']) ? $data['tous'] : false;
        $nouveaux = isset($data['nouveaux']) ? $data['nouveaux'] : false;   
        if (!$tous) {
            $qb->andWhere('o.etat = :etat')
               ->setParameter('etat', Etat::DISPONIBLE);
        }   
        if ($nouveaux) {
            $date = new \DateTime();
            $date->modify('-24 hours');
            $qb->andWhere('o.dc >= :date')
                ->setParameter('dc', $date);
        }         
        if ($tri == 'dc') {
            $qb->orderBy('o.dc', 'DESC');
        } else {
            $qb->orderBy('o.dl', 'DESC');
        }   
    
        if ($titre !== null) {
            $qb->andWhere('o.titre LIKE :titre')
               ->setParameter('titre', '%'.$titre.'%');
        }
    
        if ($categorie !== null) {
            $qb->andWhere('o.categorie = :categorie')
               ->setParameter('categorie', $categorie);
        }
         $qb->andWhere('o.statut = :statut')
             ->setParameter('statut', Statut::ACCEPTED);
             $pagination = $paginator->paginate(
                $qb->getQuery(), 
                $request->query->getInt('page', 1), 
                6
            );
        
        $offres = $qb->getQuery()->getResult();
        $nbDisponibles = $offresRepository->countDisponible();
        return $this->render('offres/dispo.html.twig', [
            'offres' => $offres, 
            'form' => $form->createView(),
             'pagination' => $pagination,
             'nbDisponibles' => $nbDisponibles,
             'now' => $now,
        ]);
    }
/**
 * @Route("/{id}/savedOffres", name="offre_save", methods={"POST"})
 */
public function savedOffres($id): JsonResponse
{
    $freelancer = 32; // Freelancer de l'id 32 est connecté
    if (!$freelancer) {
        return new JsonResponse(['message' => 'Vous devez vous connecter pour sauvegarder des offres d\'emploi.'], 401);
    }

    $offre = $this->getDoctrine()->getRepository(Offres::class)->find($id);
    if (!$offre) {
        return new JsonResponse(['message' => 'Offre d\'emploi non trouvée.'], 404);
    }

    $favoriteOffre = $this->getDoctrine()->getRepository(FavoriteOffre::class)->findOneBy([
        'freelancer' => $freelancer,
        'offre' => $offre,
    ]);

    $entityManager = $this->getDoctrine()->getManager();

    if ($favoriteOffre) {
        $entityManager->remove($favoriteOffre);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Offre d\'emploi retirée de vos favoris.']);
    }

    $favoriteOffre = new FavoriteOffre();
    $freelancer = $this->getDoctrine()->getRepository(Users::class)->find($freelancer);
    $favoriteOffre->setFreelancer($freelancer);
    $favoriteOffre->setOffre($offre);

    $entityManager->persist($favoriteOffre);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Offre d\'emploi sauvegardée avec succès.']);
}


    /**
     * @Route("/mes-favoris", name="offres_favorites_add")
     */
    public function showFavorites(Request $request,PaginatorInterface $paginator): Response
    {
        $freelancerId = 32; // Freelancer de l'id 32 est connecté
        $favorites = $this->getDoctrine()->getRepository(FavoriteOffre::class)->findBy(['freelancer' => $freelancerId]);
        $offres = [];
        foreach ($favorites as $favorite) {
            $offres[] = $favorite->getOffre();
        }
      
        return $this->render('offres/favoris.html.twig', [
            'offres' => $offres,
            'favorites' => $favorites,
           
        ]);
    }

    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route('/offres/admin', name: 'showOffresAdmin')]
    public function showOffresAdmin(Request $request, OffresRepository $offresRepository,PaginatorInterface $paginator): Response
    {
        $offre = $offresRepository->findAll();

        if (empty($offre)) {
            $message = 'Aucune offre n\'a été trouvée.';
            return $this->render('offres/adminOffres.html.twig', [
                'message' => $message
            ]);
        }

        return $this->render('offres/adminOffres.html.twig', [
            'offre' => $offre
        ]);
    }


/**
 * @Route("/offres/{id}/accepter", name="offres_accepter", methods={"POST", "GET"})
 */
public function accepter($id, EntityManagerInterface $entityManager)
{
    $offre = $this->getDoctrine()->getRepository(Offres::class)->find($id);
    if (!$offre) {
        throw $this->createNotFoundException('Offre non trouvée');
    }

    $offre->setStatut(Statut::ACCEPTED);
    $entityManager->persist($offre);
    $entityManager->flush();
    $notification = new Notifications();
    $notification->setMsg('Nouvelle offre créée: '.$offre->getTitre());
    $notification->setOffre($offre);
    $notification->setDate(new \DateTimeImmutable());   
    $notification->setVu(false);
    $entityManager->persist($notification);
    $entityManager->flush();
    $this->addFlash('success', 'L\'offre "'.$offre->getTitre().'" a été acceptée.');
    return $this->redirectToRoute('showOffresAcceptees');
}

/**
 * @Route("/offres/acceptees", name="showOffresAcceptees")
 */
public function showOffresAcceptees(OffresRepository $offresRepository): Response
{
    $offres = $offresRepository->findBy(['statut' => Statut::ACCEPTED]);
    return $this->render('offres/acceptee.html.twig', [
        'offres' => $offres
    ]);
}
/**
 * @Route("/offres/refusees", name="showOffresRefusees")
 */
public function showOffresRefusees(OffresRepository $offresRepository): Response
{
    $offres = $offresRepository->findBy(['statut' => Statut::REFUSED]);
    return $this->render('offres/refusee.html.twig', [
        'offres' => $offres
    ]);
}
/**
 * @Route("/offres/{id}/refuser", name="offres_refuser", methods={"POST", "GET"})
 */
public function refuser(Offres $offre, EntityManagerInterface $entityManager)
{
    $offre->setStatut(Statut::REFUSED);
    $entityManager->persist($offre);
    $entityManager->flush();
    $this->addFlash('success', 'L\'offre "'.$offre->getTitre().'" a été refusée.');
    return $this->redirectToRoute('showOffresRefusees');
}


#[Route('/offres', name: 'app_offres')]
public function index(Request $request, OffresRepository $offresRepository, NotificationsRepository $notificationsRepository, EntityManagerInterface $entityManager,PaginatorInterface $paginator): Response
{
    $idClient = 18; // le client connecté 
    $offres = $offresRepository->findAll();
    $pagination = $this->paginator->paginate(
    $offres,
    $request->query->getInt('page', 1),
    6
);
    $form = $this->createForm(SearchType::class);
    $form->handleRequest($request);
    $data = $form->getData();
    $titre = isset($data['search']) ? $data['search'] : null;
    $categorie = isset($data['categorie']) ? $data['categorie'] : null; 
    $tri = $request->query->get('tri', 'dc');
    $qb = $offresRepository->createQueryBuilder('o');
    if ($tri == 'dc') {
        $qb->orderBy('o.dc', 'DESC');
    } else {
        $qb->orderBy('o.dl', 'DESC');
    }   
    if ($titre !== null) {
        $qb->andWhere('o.titre LIKE :titre')
           ->setParameter('titre', '%'.$titre.'%');
    }
    if ($categorie !== null) {
        $qb->andWhere('o.categorie = :categorie')
           ->setParameter('categorie', $categorie);
    }
    if ($idClient !== null) {
        $qb->andWhere('o.clientid = :clientid')
           ->setParameter('clientid', $idClient);
    }
    $offres = $qb->getQuery()->getResult();
    $qb->andWhere('o.statut = :statut')
   ->setParameter('statut', Statut::ACCEPTED);
    foreach ($offres as $offre) {
        if ($offre->isExpired()) {
            $offre->setEtat(Etat::NON_DISPONIBLE);
            $entityManager->persist($offre);
        }
    }
    $entityManager->flush();
    $notifications = $notificationsRepository->findBy(['vu' => false]);    
       return $this->render('offres/index.html.twig', [
        'offres' => $offres,
        'form' => $form->createView(),
        'notifications' => $notifications, 
        'pagination' => $pagination,
    ]);
}
#[Route('/offres/ajouter', name: 'offres_ajouter', methods: ['GET', 'POST'])]
public function ajouter(Request $request, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
{
    $offre = new Offres($entityManager);
    $offre->setDl(new DateTimeImmutable());
    $offre->setStatut(Statut::EN_ATTENTE); 
    $form = $this->createForm(OffresType::class, $offre);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $existingOffre = $entityManager->getRepository(Offres::class)->findOneBy(['titre' => $offre->getTitre()]);
        if ($existingOffre) {
            $form->addError(new FormError('Ce offre existe déjà.'));
            return $this->render('offres/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        $logoPath = $form['logoPath']->getData();
        list($width, $height) = getimagesize($logoPath);
        if ($width != 512 || $height != 512) {
            $form->addError(new FormError('L\'image doit avoir une taille de 512px.'));
            return $this->render('offres/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        $originalFileName = pathinfo($logoPath->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFileName.'-'.uniqid().'.'.$logoPath->guessExtension();
        $logoPath->move(
            $this->getParameter('images_directory'),
            $newFileName
        );
        $offre->setlogoPath($newFileName);
        $message = (new \Swift_Message('Nouvelle offre ajoutée'))
            ->setFrom('mejri.salma@esprit.tn')
            ->setTo('topiajob491@gmail.com')
            ->setBody(
                $this->renderView(
                    'offres/newOff.html.twig',
                    ['offre' => $offre]
                ),
                'text/html'
            );
$message->setBody(
            $message->getBody() . '<br><a href="' . $this->generateUrl('showOffresAdmin', [], UrlGeneratorInterface::ABSOLUTE_URL) . '">Traiter cette offre</a>',
            'text/html'
        );  
$mailer->send($message);
$entityManager->persist($offre);
$entityManager->flush();
$this->addFlash('success', 'Votre offre a été ajoutée et sera traitée par l\'administrateur.');       
return $this->redirectToRoute('app_offres');
    }
    return $this->render('offres/create.html.twig', [
        'form' => $form->createView(),
    ]);
}


        #[Route('/offres/modifier/{id}', name: 'modifier_offre', methods: ['GET', 'POST'])]
        public function edit(Request $request, EntityManagerInterface $entityManager, Offres $offre): Response
{
    $form = $this->createForm(modifType::class, $offre);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($offre);
        $entityManager->flush();
        
        return $this->redirectToRoute('offres_show', ['offre' => $offre->getId()]);
    } else {
        foreach($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }
    }  
    return $this->render('offres/modifier.html.twig', [
        'offre' => $offre,
        'form' => $form->createView(),
    ]);
}
#[Route('/offres/{offre}', name: 'offres_show', methods: ['GET'])]
public function show(Request $request, EntityManagerInterface $entityManager, Offres $offre): Response
{
    return $this->render('offres/show.html.twig', [
        'offre' => $offre,
    ]);
}

    #[Route('/offres/free/{offre}', name: 'offres_free_show', methods: ['GET'])]
    public function showOffre(Request $request, EntityManagerInterface $entityManager, Offres $offre, OffresRepository $offresRepository): Response
    {
        $categorie = $offre->getCategorie();
        $similaires = $this->getDoctrine()
        ->getRepository(Offres::class)
        ->createQueryBuilder('o')
        ->where('o.categorie = :categorie')
        ->andWhere('o.id != :id')
        ->andWhere('o.etat = :etat')
        ->setParameter('categorie', $categorie)
        ->setParameter('id', $offre->getId())
        ->setParameter('etat', Etat::DISPONIBLE)
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();

    
        return $this->render('offres/showFree.html.twig', [
            'offre' => $offre,
            'similaires' => $similaires,
        ]);
    }
    
    
 #[Route('/offres/{id}', name:'offre_delete', methods:['POST'])]
 
public function delete(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
        $entityManager->remove($offre);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_offres');
    }
  
    
         #[Route('/offres/{id}/refusee', name: 'offres_refusee')]
        public function refusee(): Response
        {
            return $this->render('offres/refusee.html.twig');
        }

        #[Route('/freelancer', name: 'offres_freelancer')]
        public function showFree(Request $request, OffresRepository $offresRepository, EntityManagerInterface $entityManager): Response
        {
            $form = $this->createForm(SearchType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $titre = isset($data['search']) ? $data['search'] : null;
                $categorie = isset($data['categorie']) ? $data['categorie'] : null;
                return $this->redirectToRoute('showDispoOffres', [
                    'titre' => $titre,
                    'categorie' => $categorie
                ]);
            }
            return $this->render('offres/freelancer.html.twig', [
                'form' => $form->createView()
            ]);
        }
     /**
 * @Route("/notifications/{id}/delete", name="notification_delete", methods={"POST"})
 */
public function deleteNotification(Notifications $notification, EntityManagerInterface $entityManager)
{
    $entityManager->remove($notification);
    $entityManager->flush();

    $this->addFlash('success', 'La notification a été supprimée.');

    return $this->redirectToRoute('app_offres');
}
/**
 * @Route("/statistics", name="statistics")
 */
public function statistics(EntityManagerInterface $entityManager): Response
{
    $offre = new Offres($entityManager);
    $repository = $entityManager->getRepository(Offres::class);
   

    // Nombre d'offres par catégorie
    $categories = Categorie::getValues();
    $byCategory = [];
    foreach ($categories as $category) {
        $query = $entityManager->createQuery(
            'SELECT COUNT(o) AS total FROM App\Entity\Offres o WHERE o.categorie = :category AND o.statut = :statut'
        )->setParameter('category', $category)
         ->setParameter('statut', Statut::ACCEPTED);
        $byCategory[$category] = $query->getSingleScalarResult();
    }
    return $this->render('offres/statistics.html.twig', [
        'byCategory' => $byCategory,
    ]);
}

/**
 * @Route("/statisticsMonth", name="statisticsMonth")
 */
public function statisticsMonth(EntityManagerInterface $entityManager): Response
{
    $repository = $entityManager->getRepository(Offres::class);

    $offres = $repository->findAll();
    $offresAccepted = $repository->findBy(['statut' => Statut::ACCEPTED]);
    $offresRefused = $repository->findBy(['statut' => Statut::REFUSED]);
    $offresWaiting = $repository->findBy(['statut' => Statut::EN_ATTENTE]);
    
    $byMonthAccepted = array_fill(1, 12, 0);
    foreach ($offresAccepted as $offre) {
        $mois = $offre->getDc()->format('n');
        $byMonthAccepted[$mois]++;
    }

    $byMonthRefused = array_fill(1, 12, 0);
    foreach ($offresRefused as $offre) {
        $mois = $offre->getDc()->format('n');
        $byMonthRefused[$mois]++;
    }

    $byMonthWaiting = array_fill(1, 12, 0);
    foreach ($offresWaiting as $offre) {
        $mois = $offre->getDc()->format('n');
        $byMonthWaiting[$mois]++;
    }

    return $this->render('offres/statisticsMonth.html.twig', [
        'byMonthAccepted' => $byMonthAccepted,
        'byMonthRefused' => $byMonthRefused,
        'byMonthWaiting' => $byMonthWaiting,
    ]);
}
//prolongation de l'offreeeeeeeeee 
public function envoyerSMS(Offres $offre)
{
    $sid = 'ACb0a6585e5153ffb82ba56072af4bc159';
    $token = '6850bb584b6ff28e9d30cec694a28001';
    $twilioNumber = '+15075095722';
    $twilio = new Client($sid, $token);    
    $to_number = "+21651617676";
    $dl = $offre->getDl(); 
    $date = new DateTime();
    $dateStr = $date->format('Y-m-d H:i:s');
    $dateSent = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($dateStr)));
    $message = "Votre offre expire bientôt. Souhaitez-vous prolonger votre offre ? Si oui vous pouvez accéder à votre plateforme et le modifier.";
    $twilio->messages->create(
        $to_number, 
        array(
            "from" => $twilioNumber,
            "body" => $message,
            "dateSent" => $dateSent 
        )
    );
    return "Le message a été envoyé avec succès au numéro " . $to_number  . " le " . $dateSent . ".";
}


////tester le sms de prolongation
/**
     * @Route("/test-envoyer-sms", name="test_envoyer_sms")
     */
    public function testEnvoyerSMS(EntityManagerInterface $entityManager): Response
    {
        $offre = new Offres($entityManager);
        $offre->setDl(new \DateTime()); 
        $result = $this->envoyerSMS($offre);
        dd($result);
    }

 }