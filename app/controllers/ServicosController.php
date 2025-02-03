<?php

class ServicosController extends Controller
{

    private $servicoModel;

    public function __construct()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Instaciar o modelo Servico
        $this->servicoModel = new Servico();

    }

    // FRONT-END: Carregar a lista de serviços
    public function index()
    {

        $dados = array();
        $dados['titulo'] = 'Serviços - Ki Oficina';

        // Obter todos os servicos
        $todosServico = $this->servicoModel->getTodosServicos();

        // Passa os serviços para a página
        $dados['servicos'] = $todosServico;
        $this->carregarViews('servicos', $dados);


    }

    // FRONT-END: Carregar o detalhe do serviços
    public function detalhe($link)
    {
        //var_dump("Link: ".$link);

        $dados = array();

        $detalheServico = $this->servicoModel->getServicoPorLink($link);

        //var_dump($detalheServico);

        if ($detalheServico) {

            $dados['titulo'] = $detalheServico['nome_servico'];
            $dados['detalhe'] = $detalheServico;
            $this->carregarViews('detalhe-servicos', $dados);

        } else {
            $dados['titulo'] = 'Serviços Ki Oficina';
            $this->carregarViews('servicos', $dados);
        }


    }




    // ###############################################
    // BACK-END - DASHBOARD
    #################################################//

    // 1- Método para listar todos os serviços
    public function listar()
    {

        if (!isset($_SESSION['userTipo']) || $_SESSION['userTipo'] !== 'funcionario') {

            header('Location:' . BASE_URL);
            exit;
        }

        $dados = array();
        $func = new Funcionario();
        $dadosFunc = $func->buscarFunc($_SESSION['userEmail']);

        $dados['listaServico'] = $this->servicoModel->getTodosServicos();
        $dados['conteudo'] = 'dash/servico/listar';
        $dados['func'] = $dadosFunc;
        $this->carregarViews('dash/dashboard', $dados);


    }

    // 2- Método para adicionar servoços
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
    public function editar($id = null)
    {
        $dados = array();
        if (!isset($_SESSION['userTipo']) || $_SESSION['userTipo'] !== 'funcionario') {

            header('Location:http://localhost/kioficina/public');
            exit;
        }

        //Se não houve ID na URL, redirecionar para página de erro (Lista)
        if ($id === null) {
            header('Location:http://localhost/kioficina/public/servicos/listar');
            exit;
        }

        // Caso seja POST, processar via FORM
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            //Entra aqui para enviar os dados

        }



        //Buscar dados do Serviços de acordo com o ID

        $servico = $this->servicoModel->getServicoById($id);
        $dados['servico'] = $servico;
        // var_dump($dados['servico']);

        
        $dados['conteudo'] = 'dash/servico/editar';

        $this->carregarViews('dash/dashboard', $dados);


    }

    // 4- Método para desativar o serviço
    public function desativar()
    {

        $dados = array();
        $dados['conteudo'] = 'dash/servico/desativar';

        $this->carregarViews('dash/dashboard', $dados);


    }




    /** MÉTODO PARA UPLOAD DA FOTO */
    private function uploadFoto($file)
    {
        $dir = '../public/uploads/servico/';
        if (file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid() . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], $dir . $nome_arquivo)) {
            return 'galeria/' . $nome_arquivo;
        }
        return false;
    }


    /**Médoto para gerar link Serviço */

    public function gerarLinkServico($nome_servico)
    {

        //Remover os acentos
        $semAcento = iconv('UTF-8', 'ASCII//TRANSLIT', $nome_servico);

        /** Substituir qualquer caracrter que não seja letra ou número por hífen */
        $link = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '-', $semAcento)));

        // var_dump($link);
        /**Verificar se já existe no banco */

        $contador = 1;
        $link_original = $link;
        while ($this->servicoModel->existeEsseServico($link)) {
            $link = $link_original . '-' . $contador;
            //tricar de bateria- 1 exemplo
            $contador++;

        }

        var_dump($link);
        return $link;
    }


}
// fim da class
