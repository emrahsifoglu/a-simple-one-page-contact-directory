<?php

namespace ContactDirectory\PersonBundle\Controller;

use ContactDirectory\PersonBundle\Form\Type\PersonType;
use ContactDirectory\PersonBundle\Model\PersonModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CRUDController extends Controller {

    /**
     *@return object
     */
    public function getPersonManager(){
        return $this->get('person_manager');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function createAction(Request $request){
        $params = json_decode($request->getContent(), true);
        $form = $this->createForm(new PersonType(), new PersonModel());
        $form->submit($params);
        if ($form->isValid()){
            $firstName = $form->get('firstName')->getData();
            $lastName =  $form->get('lastName')->getData();
            $id = $this->getPersonManager()->create($firstName, $lastName);
            $response = array('id' => $id);
        } else {
            $response = array('id' => -1, 'errors' => $form->getErrorsAsString());
        }
        return new JsonResponse($response);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    function fetchAction($id) {
        $return = (filter_var($id, FILTER_VALIDATE_INT)) ?
            $this->getPersonManager()->fetch($id) : array('errors' => 'Id is required!');
        return new JsonResponse($return);
    }

    /**
     * @return JsonResponse
     */
    function fetchAllAction(){
        $people = $this->getPersonManager()->fetchAll();
        return new JsonResponse($people);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function updateAction(Request $request){
        $params = json_decode($request->getContent(), true);
        if (filter_var($params['id'], FILTER_VALIDATE_INT)) {
            $form = $this->createForm(new PersonType(), new PersonModel());
            $form->submit($params);
            if ($form->isValid()){
                $id = $form->get('id')->getData();
                $firstName = $form->get('firstName')->getData();
                $lastName =  $form->get('lastName')->getData();
                $return = $this->getPersonManager()->update($id, $firstName, $lastName);
            } else {
                $return = array('errors' => $form->getErrorsAsString()); // ?
            }
        } else {
            $return = array('errors' => 'Id is required!');
        }
        return new JsonResponse(array('return' => $return));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    function deleteAction($id){
        $return = (filter_var($id, FILTER_VALIDATE_INT)) ?
            $this->getPersonManager()->delete($id) : array('errors' => 'Id is required!');
        return new JsonResponse($return);
    }

}