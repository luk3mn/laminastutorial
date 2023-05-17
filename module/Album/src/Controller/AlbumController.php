<?php

namespace Album\Controller;

// to inject it into our controller so we can use it
use Album\Model\AlbumTable;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

// Add the following import statements at the top of the file:
use Album\Form\AlbumForm;
use Album\Model\Album;

class AlbumController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Firstly, we'll add a constructor to our controller
    public function __construct(AlbumTable $table) // Our controller now depends on AlbumTable, so we will need to create a factory for the controller
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([ // In order to list the albums, we need to retrieve them from the model and pass them to the view
            'albums' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AlbumForm(); // We instantiate AlbumForm
        $form->get('submit')->setValue('Add'); //  set the label on the submit button to "Add"

        $request = $this->getRequest();

        if (! $request->isPost()) { // If the request is not a POST request, then no form data has been submitted
            return ['form' => $form]; //  we need to display the form
        }

        $album = new Album(); // We create an Album instance
        $form->setInputFilter($album->getInputFilter()); // pass its input filter on to the form
        $form->setData($request->getPost());

        if (! $form->isValid()) { // If form validation fails, we want to redisplay the form
            return ['form' => $form];
        }

        // If the form is valid, then we grab the data from the form and store to the model using saveAlbum().
        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album'); // After we have saved the new album row, we redirect back to the list of albums using the Redirect controller plugin.
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->table->saveAlbum($album);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0); // As before, we get the id from the matched route, and check the request object's isPost() to determine whether to show the confirmation page or to delete the album
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) { // If the request is not a POST, then we retrieve the correct database record and assign to the view, along with the id
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return [
            'id'    => $id,
            'album' => $this->table->getAlbum($id),
        ];
    }
}