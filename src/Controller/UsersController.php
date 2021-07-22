<?php
declare(strict_types=1);

namespace CakeCart\Controller;

use CakeCart\Controller\AppController;

/**
 * Users Controller
 *
 * @property \CakeCart\Model\Table\UsersTable $Users
 * @method \CakeCart\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['login','register']);
    }
    public function login(){
        $this->set(['captcha'=>$this->Captcha->create($this->request->getSession())]);
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            if($this->Captcha->check($this->request->getSession(),$this->request->getData('captcha'))){
                return $this->redirect($redirect);
            }
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid email or password'));
        }
    }

    public function logout(){
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Login', 'action' => 'index']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Statuses'],
        ];
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Statuses', 'Roles', 'Events', 'Chats', 'Questions', 'Sessions', 'Topics', 'Devices', 'Programmes', 'Socials', 'Users', 'Accommodations', 'Addresses', 'Badges', 'Categories', 'Comments', 'Companies', 'CompaniesEvents', 'Competitions', 'Contacts', 'Designations', 'Entries', 'Feedbacks', 'Files', 'Halls', 'Images', 'Members', 'Menus', 'Messages', 'Mobiles', 'Notifications', 'Organizations', 'Profiles'],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $statuses = $this->Users->Statuses->find('list', ['limit' => 200]);
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $events = $this->Users->Events->find('list', ['limit' => 200]);
        $chats = $this->Users->Chats->find('list', ['limit' => 200]);
        $questions = $this->Users->Questions->find('list', ['limit' => 200]);
        $sessions = $this->Users->Sessions->find('list', ['limit' => 200]);
        $topics = $this->Users->Topics->find('list', ['limit' => 200]);
        $devices = $this->Users->Devices->find('list', ['limit' => 200]);
        $programmes = $this->Users->Programmes->find('list', ['limit' => 200]);
        $socials = $this->Users->Socials->find('list', ['limit' => 200]);
        $this->set(compact('user', 'statuses', 'roles', 'events', 'chats', 'questions', 'sessions', 'topics', 'devices', 'programmes', 'socials'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Roles', 'Events', 'Chats', 'Questions', 'Sessions', 'Topics', 'Devices', 'Programmes', 'Socials'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $statuses = $this->Users->Statuses->find('list', ['limit' => 200]);
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $events = $this->Users->Events->find('list', ['limit' => 200]);
        $chats = $this->Users->Chats->find('list', ['limit' => 200]);
        $questions = $this->Users->Questions->find('list', ['limit' => 200]);
        $sessions = $this->Users->Sessions->find('list', ['limit' => 200]);
        $topics = $this->Users->Topics->find('list', ['limit' => 200]);
        $devices = $this->Users->Devices->find('list', ['limit' => 200]);
        $programmes = $this->Users->Programmes->find('list', ['limit' => 200]);
        $socials = $this->Users->Socials->find('list', ['limit' => 200]);
        $this->set(compact('user', 'statuses', 'roles', 'events', 'chats', 'questions', 'sessions', 'topics', 'devices', 'programmes', 'socials'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        $entity = $this->Users->patchEntity($user, ['statuse_id'=>3]);
        if ($this->Users->save($entity)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function register(){
            $this->set('verify',false);
         if(!empty($this->request->getQuery('verify'))) {
            $this->set('verify',true);
        }
    }
}
