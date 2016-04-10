<?php
namespace CodeDelivery\Services;


use CodeDelivery\Repositories\ClientRepository;
use CodeDelivery\Repositories\UserRepository;

class ClientService
{

    private $clientRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ClientRepository $clientRepository, UserRepository $userRepository)
    {

        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
    }

    public function update(array $data, $id)
    {
        #altero na tabela de clientes
        $this->clientRepository->update($data, $id);

        #pego o id do user
        $userId = $this->clientRepository->find($id, ['user_id'])->user_id;

        #altero na tabela de users
        $this->userRepository->update($data['user'], $userId);
    }

    public function create(array $data)
    {
        #crio o usuário
        $data['user']['password'] = bcrypt(123456); #atribui uma senha padrão
        $user = $this->userRepository->create($data['user']);

        #crio o cliente
        $data['user_id'] = $user->id;
        $this->clientRepository->create($data);

    }

}