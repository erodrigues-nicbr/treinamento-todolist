<?php 

class Utils {
    private static $repositories = [];

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para exibir um array formatado
     * @param array $data Array a ser exibido
     * @return void
     */

    public static function pr( $data ) {
        echo '<pre>' . print_r( $data, true ) . '</pre>';
    }

    /**
     * @var array
     * @access public
     * @static
     * @comment Função que retorna o caminho do arquivo de um componente
     * @param string $componentName Nome do componente
     * @return string Caminho do arquivo
     */
    public static function getComponentUi($componentName) {
        return ROOTDIR . DS . 'ui' . DS . 'components'. DS . "{$componentName}.php";
    }

    public static function getRepository($tableName) {
        if( ! isset(self::$repositories[$tableName]) ) {
            self::$repositories[$tableName] = new Repository($tableName);
        }
        return self::$repositories[$tableName];
    }

    public static function getPrioridades(){
        return [
            1 => 'Baixa',
            2 => 'Média',
            3 => 'Alta',
        ];
    }

    public static function getPrioridade($prioridade){
        $prioridades = self::getPrioridades();
        return isset($prioridades[$prioridade]) ? $prioridades[$prioridade] : null;
    }
}

class Configure {
    /**
    * @var array
    * @access protected
    * @static
    * @comment Array com as configurações cadastradas no sistema
    */
    protected static $configs = [];
    
    /**
    * @var array
    * @access public
    * @static
    * @comment Função para ler as configurações do sistema
    * @param string $key Chave da configuração (obrigatório)
    * @param mixed $defaultValue Valor padrão caso a chave não exista
    * @return mixed Valor da configuração
    */
    public static function read($key, $defaultValue = null) {
        $keys = explode('.', $key);

        $current = self::$configs;
        foreach ($keys as $key) {
            if( ! isset($current[$key]) ) return $defaultValue;
            $current = $current[$key];
        }
        
        return $current;
    }
    
    /**
    * @var array
    * @access public
    * @static
    * @comment Função para escrever as configurações do sistema
    * @param string $key Chave da configuração (obrigatório)
    * @param mixed $value Valor da configuração (obrigatório)
    * @return void
    */
    public static function write( $key, $value ) {
        $keys = explode('.', $key);
        $current = &self::$configs;
    
        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
    
        $current = $value;
    }
}

class Routes{
    /**
     * @var array
     * @access protected
     * @comment Array com as rotas cadastradas no sistema (em formato de regex)
     * @comment Exemplo: array('/^\/$/' => 'pages/list.php', '/^\/sobre$/' => '/pages/sobre.php')
     */
    protected $routes = array();

    /**
     * @var string
     * @access protected
     * @comment Rota padrão
     * @param route string Rota padrão (aceita regex)
     * @param file string Arquivo que será incluído
     */
    public function add($pattern, $file, $options = []) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $pattern);
        $pattern = preg_replace('/\/$/', '', $pattern) . "/";
        $pattern = '#^' . $pattern . '$#';
        $this->routes[$pattern] = [
            'file' => $file,
            'options' => $options
        ];
    }

    /**
     * @var string
     * @access protected
     * @comment Pega rota cadastrada por regex e retorna o arquivo que será incluído
     * @param route string Rota padrão (aceita regex)
     * @param file string Arquivo que será incluído
     */
    public function dispatch($uri = null) {
        if( is_null($uri) )$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = preg_replace('/\/$/', '', $uri) . "/";
        foreach ($this->routes as $pattern => $route) {
            $file = $route['file'];
            $options = $route['options'];
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return $this->includeFile($file, $params, $options);
            }
        }

        return $this->notFound();
    }

    /**
     * @var string
     * @access private
     * @comment Inclui o arquivo e passa os parâmetros
     * @param file string Arquivo que será incluído
     * @param params array Parâmetros que serão passados para o arquivo
     */
    private function includeFile($file, $params, $options = []) {
        $params = array_merge($params, ['fileRouterInclude' => ROOTDIR . DS . 'ui' . DS . $file], $options);
        if(isset($options['layout']) && $options['layout'] === false) {
            include_once(ROOTDIR . DS . 'ui' . DS . $file);
            return;
        }

        extract($params);
        include_once(ROOTDIR . DS . 'ui' . DS . "layouts" . DS . "default.php");
    }

    /**
     * @var string
     * @access private
     * @comment Retorna 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        echo "404 Not Found";
    }
    
}

class Request{

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para pegar os dados enviados via POST
     * @return array
     */
    public static function getJsonData($convertArray = true ) {
        return json_decode(file_get_contents('php://input'), $convertArray);
    }

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para pegar um segmento da URI
     * @param int $index Índice do segmento
     * @return string
     */
    public static function getUriSegment($index) {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = preg_replace('/\/$/', '', $uri) . "/";
        $segments = explode('/', $uri);
        return isset($segments[$index]) ? $segments[$index] : null;
    }

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para pegar o método da requisição
     * @return string
     */
    public static function isMethod($method) {

        $method = is_array($method) ? $method : [$method];
        $requestMethod = self::getMethodName();

        return in_array($requestMethod, array_map('strtoupper', $method));
    }
    

    /**
     * @var array
     * @access public
     * @static
     * @comment Função para pegar o método da requisição
     * @return string
     */
    public static function getMethodName() {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
}

class Response{
    /**
     * @var array
     * @access public
     * @static
     * @comment Função para retornar um JSON
     * @param array $data Array a ser retornado
     * @return void
     */
    public static function responseJson($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        die();
    }
}