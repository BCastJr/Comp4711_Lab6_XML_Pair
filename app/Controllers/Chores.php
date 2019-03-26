<?php
namespace App\Controllers;
use App\Models\dwarves;
use App\Models\flags;
use App\Models\groups;
use App\Models\priorities;
use App\Models\sizes;
use App\Models\statuses;
use App\Models\tasks;
use CodeIgniter\controller;
use CodeIgniter\API\ResponseTrait;

class Chores extends Controller
{
    use ResponseTrait;
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, 
            \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) 
    {
        parent::initController($request, $response, $logger);
        $this->dwarves = new Dwarves();
        $this->tasks = new Tasks();
        $this->priorities = new Priorities();
        $this->groups = new Groups();
    }
    public function index()
    {
        return view('dwarfs_view', ['records'=>$this->dwarfs->findAll()]);
    }
    public function assign(string $id)
    {
        $xml = new \SimpleXMLElement('<assignment/>');
        $dwarf = $this->dwarves->find($id);
        $tasks = [];
        $tasks[0] = $this->tasks->find(rand(1, 7));
        $tasks[1] = $this->tasks->find(rand(1, 7));
        $tasks[2] = $this->tasks->find(rand(1, 7));
        $xml->name = $dwarf['name'];
        $xml->role = $dwarf['role'];
        $chores= $xml->addChild('chores');
        for($i = 0; $i < sizeof($tasks); $i++)
        {
            $task=$chores->addChild('task');
            $task->id = $tasks[$i]['id'];
            $task->description =$tasks[$i]['task'];
            $priority = $this->priorities->find($tasks[$i]['priority']);
            $group = $this->groups->find($tasks[$i]['group']);
            $task->priority =$priority['name'];
            $task->group=$group['name'];
        }
        $this->response->setContentType('text/xml');
        return $xml->asXML();
    }
}