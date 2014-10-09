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

class ServiceEquipo extends REST_Controller
{
    function user_get()
    {
        $query = "";
        if($this->get('idEquipo'))
        {
            if($this->checkExist($this->get('idEquipo'))){
              $query = "SELECT * FROM tbl_Equipo where int_Id_Equipo ='".$this->get('idEquipo')."';";
            }
            else{
                $this->response(array('error' => 'La Equipo no existe'), 404);
            }
        }
        else{
            $query = "SELECT * FROM tbl_Equipo;";
        }

        $queryRes = $this->db->query($query);
        $users = array();
        $user = array();
        if ($queryRes->num_rows() > 0)
        {
            foreach ($queryRes->result() as $row)
            {
               $user['id'] = $row->int_Id_Equipo; // call attributes ID
               $user['nombre'] = $row->var_Nombre; // call attributes Nombre
               $user['abrev'] = $row->var_Abrev; // call attributes Nombre
               array_push($users,$user);
            } 
        }
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Equipo could not be found'), 404);
        }
    }
    
    function user_post()
    {
        $query = "";
        $info = json_decode(file_get_contents('php://input'), true);
        $data = array(
                   'var_Nombre' => $info['nombre'],
                   'var_Abrev' => $info['abrev']
                );
        switch ($info['action']) {
            case 'add':
                    $query = $this->db->insert('tbl_Equipo', $data); 
                break;
            case 'update':
                if($this->checkExist($info['idEquipo'])){
                    $query = $this->db->update('tbl_Equipo', $data, array('int_Id_Equipo' => $info['idEquipo'])); 
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
        $query = $this->db->delete('tbl_Equipo', array('int_Id_Equipo' => $info->idEquipo)); 
        $this->response($query, 200); // 200 being the HTTP response code
    }

    function checkExist($id){
        $query = $this->db->get_where('tbl_Equipo', array('int_Id_Equipo' => $id));
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