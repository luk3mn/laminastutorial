<?php

namespace Album\Controller;

// to inject it into our controller so we can use it
use Album\Model\AlbumTable;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

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
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}