<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Category;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function product(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $products= $em->getRepository(Product::class)->findAll();

        $del_categories = $em->getRepository(Category::class)->findBy(array('active' => 0 ));



        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $count = 0;
        $forms = array();


        foreach ($products as $product){
            $forms[$count] = $form->createView();
            $count += 1;
        }

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {

            $id = $_POST['product_id'];
            #$id =->setName($form["name"]->getData());
            $product = $em->getRepository(Product::class)->findOneBy(array('id' => $id ));

            if (!$product) {
                    throw $this->createNotFoundException(
                        'No se pudo editar por id '.$id
                    );
                }


            if ($form->isValid()) {

                $product->setName($form["name"]->getData());
                $product->setCode($form["code"]->getData());
                $product->setDescription($form["description"]->getData());

                $em->persist($product);
                $em->flush();
                $this->addFlash("exito","El producto se editó exitosamente ");

            } else {
                $this->addFlash("exito","error guardando");
            }

        }

        return $this->render('/product/index.html.twig', [
            'products' => $products,
            "forms"  => $forms,
            'del_categories' => $del_categories,
        ]);




    }

    /**
     * @Route("/product/add", name="addProduct")
     */
    public function add(Request $request): Response
    {
    	$product = new Product();
    	$em = $this->getDoctrine()->getManager();
    	$form = $this->createForm(ProductType::class, $product);
    	$form->handleRequest($request);

        $del_categories = $em->getRepository(Category::class)->findBy(array('active' => 0 ));

    	if ($request->isMethod('POST')) {

            //$product_price = $_POST['product_price'];
            //echo $product_price;

	    	if ($form->isValid()) {



	    		$em->persist($product);
	    		$em->flush();
	    		$this->addFlash("exito","El producto se guardó exitosamente ");
	    		return $this->redirect($this->generateUrl('addProduct'));
	    		/*return $this->render('product/index.html.twig', [
	    		     'controller_name' => 'ProductController',
	    		 ]);*/
	    	}
	    }



         return $this->render('/product/add.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
            'del_categories' => $del_categories,
            ]);

    }



        /**
         * @Route("/product/delet/{id}", name="delet_product")
         */
        public function delet_product($id,Request $request): Response
        {
            $product = new Product();
            $em = $this->getDoctrine()->getManager();
            $redireccion = new RedirectResponse('/');
            $product = $em->getRepository(Product::class)->findOneBy(array('id' => $id ));

            if (!$product) {
                $this->addFlash("exito",'No se pudo borrar el producto con id '.$id);
                return $redireccion;
            }

            $em->remove($product);

            $em->flush();

            $this->addFlash("exito",'Se eliminó correctamente el producto con id: '.$id);



            return $redireccion;


        }
}
