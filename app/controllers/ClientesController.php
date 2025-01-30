<?php

class ClientesController extends Controller
{
    private $clienteModel;
    public function __construct()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Instaciar o modelo Servico
        $this->clienteModel = new Cliente();

    }

    public function index()
    {
 
        $dados = array();
        $dados['titulo'] = 'Serviços - Ki Oficina';
 
        // Obter todos os servicos
        $todosServico = $this->clienteModel->getTodosClientes();
 
        // Passa os serviços para a página
        $dados['servicos'] = $todosServico;
        $this->carregarViews('servicos', $dados);
 
 
    }

    public function listar()
    {
 
        // Verificação de autenticação
        if (!isset($_SESSION['userTipo']) || $_SESSION['userTipo'] !== 'funcionario') {
            header('Location:' . BASE_URL);
            exit;
        }
 
        // Inicializa o array de dados para a view
        $dados = array();
 
        // Busca os dados do funcionário logado
        $func = new Funcionario();
        $dadosFunc = $func->buscarFunc($_SESSION['userEmail']);
 
        // Consulta os clientes no modelo clienteModel
        $dados['listaCliente'] = $this->clienteModel->getTodosClientes();
 
        // Define o caminho do conteúdo da página
        $dados['conteudo'] = 'dash/cliente/listar';
        $dados['func'] = $dadosFunc;
 
        // Renderiza a view principal com os dados
        $this->carregarViews('dash/dashboard', $dados);
    }
 

    public function adicionar()
    {

        if (!isset($_SESSION['userTipo']) || $_SESSION['userTipo'] !== 'funcionario') {

            header('Location:' . BASE_URL);
            exit;
        }

        $dados= array();


    

        /** BUSCAR FUNCIONARIO */
        $func = new Funcionario();
        $dadosFunc = $func->buscarFunc($_SESSION['userEmail']);

        

        $dados['func'] = $dadosFunc;
        $dados['conteudo'] = 'dash/cliente/adicionar';



        $this->carregarViews('dash/dashboard', $dados);


    }

    

    // 3- Método para editar
    public function editar()
    {

        $dados = array();
        $dados['conteudo'] = 'dash/servico/editar';

        $this->carregarViews('dash/dashboard', $dados);


    }

    public function desativar(){
 
        $dados = array();
        $dados['conteudo'] = 'dash/servico/desativar';
 
        $this->carregarViews('dash/dashboard', $dados);
 
 
    }



}