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

        $dados = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** TBL SERVICO */
            $nome_servico = filter_input(INPUT_POST, 'nome_servico', FILTER_SANITIZE_SPECIAL_CHARS);
            $descricao_servico = filter_input(INPUT_POST, 'descricao_servico', FILTER_SANITIZE_SPECIAL_CHARS);
            $preco_base_servico = filter_input(INPUT_POST, 'preco_base_servico', FILTER_SANITIZE_NUMBER_FLOAT);
            $tempo_estimado_servico = filter_input(INPUT_POST, 'tempo_estimado_servico');
            // $alt_foto_servico               = filter_input
            $id_especialidade = filter_input(INPUT_POST, 'id_especialidade', FILTER_SANITIZE_NUMBER_INT);
            $status_servico = filter_input(INPUT_POST, 'status_servico', FILTER_SANITIZE_SPECIAL_CHARS);

            $nova_especialidade = filter_input(INPUT_POST, 'nova_especialidade', FILTER_SANITIZE_SPECIAL_CHARS);





            if ($nome_servico && $descricao_servico && $preco_base_servico !== false) {

                /** 1   -VERIFICAR A ESPECIALIADE  */

                if (empty($id_especialidade) && !empty($nova_especialidade)) {
                    /** Criar e Obter a especialidade nova */
                    $id_especialidade = $this->servicoModel->obterOuCriarEspecialidade($nova_especialidade);

                }

                /** 2  - LINK DO SERVIÇO  */

                $link_servico = $this->gerarLinkServico($nome_servico);

                /**  3- PREPARAR DADOS */
                $dadosServico = array(
                    'nome_servico' => $nome_servico,
                    'descricao_servico' => $descricao_servico,
                    'preco_base_servico' => $preco_base_servico,
                    'tempo_estimado_servico' => $tempo_estimado_servico,
                    'id_especialidade' => $id_especialidade,
                    'status_servico' => $status_servico,
                    'link_servico' => $link_servico
                );

                /** 4- inserir o serviço e obter o ID */

                $id_servico = $this->servicoModel->addServico($dadosServico);

                if (isset($_FILES['foto_galeria']) && $_FILES['foto_galeria']['error'] == 0) {
                    $arquivo = $this->uploadFoto($_FILES['foto_galeria']);

                    if ($arquivo) {
                        //Inserir na galeria
                        $this->servicoModel->addFotoGaleria($id_servico, $arquivo, $nome_servico);

                    } else {
                        //Definir uma mensagem informando que a foto não pode ser salva
                    }
                }





            }

        }

        /** BUSCAR FUNCIONARIO */
        $func = new Funcionario();
        $dadosFunc = $func->buscarFunc($_SESSION['userEmail']);

        /* buscar as especialidades */
        $especialidades = new Especialidade();
        $dados['especialidades'] = $especialidades->getTodasEspecialidades();


        $dados['func'] = $dadosFunc;
        $dados['conteudo'] = 'dash/servico/adicionar';



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