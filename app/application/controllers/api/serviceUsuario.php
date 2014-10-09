<?php 

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class ServiceUsuario extends REST_Controller
{
    function user_get()
    {
        $query = "";
        if($this->get('idUsuario'))
        {
            if($this->checkExist($this->get('idUsuario'))){
              $query = "SELECT * FROM tbl_Usuario where var_Id_Usuario ='".$this->get('idUsuario')."';";
            }
            else{
                $this->response(array('error' => 'El usuario no existe'), 404);
            }
        }
        else{
            $query = "SELECT * FROM tbl_Usuario;";
        }

        $queryRes = $this->db->query($query);
        $users = array();
        $user = array();
        if ($queryRes->num_rows() > 0)
        {
            foreach ($queryRes->result() as $row)
            {
               $user['id'] = $row->var_Id_Usuario; // call attributes ID
               $user['password'] = $row->var_Password; // call attributes Password
               array_push($users,$user);
            } 
        }
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
    function user_post()
    {
        $query = "";
        $info = json_decode(file_get_contents('php://input'), true);
        $data = array(
                   'var_Id_Usuario' => $info['idUsuario'],
                   'var_Password' => $info['password']
                );
        switch ($info['action']) {
            case 'add':
                if(!$this->checkExist($info['idUsuario'])){
                    $query = $this->db->insert('tbl_Usuario', $data); 
                }
                else{
                    $query = "error ya existe";
                }
                break;
            case 'update':
                if($this->checkExist($info['idUsuario'])){
                    $query = $this->db->update('tbl_Usuario', $data, array('var_Id_Usuario' => $data['var_Id_Usuario'])); 
                }
                else{
                    $query = "error no existe";
                }
                break;
            
            default:
                # code...
                break;
        }
        
        $this->response($query, 200); // 200 being the HTTP response code
    }
    
    function user_delete()
    {
        $query = "";
        $info = $this->post('info');
        $query = $this->db->delete('tbl_Usuario', array('var_Id_Usuario' => $info->idUsuario)); 
        $this->response($query, 200); // 200 being the HTTP response code
    }

    function checkExist($id){
        $query = $this->db->get_where('tbl_Usuario', array('var_Id_Usuario' => $id));
        if($query->num_rows()>0)
            return true;
        return false;
    }
    

    public function send_post()
    {
        var_dump($this->request->body);
    }


    public function send_put()
    {
        var_dump($this->put('foo'));
    }
}