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

class ServicePuntaje extends REST_Controller
{
    function user_get()
    {
        $query = "";
        if($this->get('idPuntaje'))
        {
            if($this->checkExist($this->get('idPuntaje'))){
              $query = "SELECT * FROM tbl_Puntaje where int_Id_Puntaje ='".$this->get('idPuntaje')."';";
            }
            else{
                $this->response(array('error' => 'La Puntaje no existe'), 404);
            }
        }
        else{
            $query = "SELECT * FROM tbl_Puntaje;";
        }

        $queryRes = $this->db->query($query);
        $users = array();
        $user = array();
        if ($queryRes->num_rows() > 0)
        {
            foreach ($queryRes->result() as $row)
            {
               $user['id'] = $row->int_Id_Puntaje; // call attributes ID
               $user['id_Usuario'] = $row->int_Id_Usuario; // call attributes Id_Usuario
               $user['id_Liga'] = $row->int_Id_Liga; // call attributes Id_Usuario
               array_push($users,$user);
            } 
        }
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Puntaje could not be found'), 404);
        }
    }
    
    function user_post()
    {
        $query = "";
        $info = json_decode(file_get_contents('php://input'), true);
        $data = array(
                   'int_Id_Usuario' => $info['id_Usuario'],
                   'int_Id_Liga' => $info['id_Liga']
                );
        switch ($info['action']) {
            case 'add':
                    $query = $this->db->insert('tbl_Puntaje', $data); 
                break;
            case 'update':
                if($this->checkExist($info['idPuntaje'])){
                    $query = $this->db->update('tbl_Puntaje', $data, array('int_Id_Puntaje' => $info['idPuntaje'])); 
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
        $query = $this->db->delete('tbl_Puntaje', array('int_Id_Puntaje' => $info->idPuntaje)); 
        $this->response($query, 200); // 200 being the HTTP response code
    }

    function checkExist($id){
        $query = $this->db->get_where('tbl_Puntaje', array('int_Id_Puntaje' => $id));
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