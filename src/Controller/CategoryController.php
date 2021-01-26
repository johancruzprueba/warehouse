<?php

namespace App\Controller;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

use Symfony\Component\HttpFoundation\RedirectResponse;




class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
      * @Route("/", name="category")
     */
    public function index(Request $request): Response
    {
    	$em = $this->getDoctrine()->getManager();
    	$categories= $em->getRepository(Category::class)->findAll();

    	$category = new Category();
    	$form = $this->createForm(CategoryType::class, $category);
    	$count = 0;
    	$forms = array();


    	foreach ($categories as $category){
    		$forms[$count] = $form->createView();
    		$count += 1;
    	}

    	$form->handleRequest($request);

    	if ($request->isMethod('POST')) {

    		$id = $_POST['category_id'];
    		#$id =->setName($form["name"]->getData());
    		$category = $em->getRepository(Category::class)->findOneBy(array('id' => $id ));

    		if (!$category) {
    		        throw $this->createNotFoundException(
    		            'No se pudo editar por id '.$id
    		        );
    		    }


			if ($form->isValid()) {

				$category->setName($form["name"]->getData());
				$category->setCode($form["code"]->getData());
				$category->setDescription($form["description"]->getData());
				$category->setActive($form["active"]->getData());

				$em->persist($category);
				$em->flush();
				$this->addFlash("exito","La categoría se editó exitosamente ");
				return $this->render('category/index.html.twig', [
	            'categories' => $categories,
	            "forms"  => $forms,
	        	]);
	    	} else {
	    		$this->addFlash("exito","error guardando");
	    	}

	    }

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
	        "forms"  => $forms,
        ]);


    }

    /**
     * @Route("/category/add", name="addCategory")
     */
    public function add(Request $request): Response
    {	$category = new Category();
    	$em = $this->getDoctrine()->getManager();
    	$form = $this->createForm(CategoryType::class, $category);
    	$form->handleRequest($request);

    	if ($request->isMethod('POST')) {

	    	if ($form->isValid()) {

	    		$em->persist($category);
	    		$em->flush();
	    		$this->addFlash("exito","La categoría se guardó exitosamente ");
	    		return $this->redirect($this->generateUrl('addCategory'));
	    	}
	    }



        return $this->render('category/add.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView()
        ]);
    }


    /**
	 * @Route("/category/delet/{id}", name="delet_category")
	 */
	public function delet_category($id,Request $request): Response
	{
		$category = new Category();
    	$em = $this->getDoctrine()->getManager();
		$redireccion = new RedirectResponse('/');
		$category = $em->getRepository(Category::class)->findOneBy(array('id' => $id ));

		if (!$category) {

		        $this->addFlash("exito",'No se pudo borrar categoría con id '.$id);
		        return $redireccion;
		    }

		$em->remove($category);

		$em->flush();

		$this->addFlash("exito",'Se eliminó correctamente la categoría id: '.$id);





		return $redireccion;


	}
}
