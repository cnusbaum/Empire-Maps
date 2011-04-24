<?php
/**
 * @author    Jeff Dupont <phxis.net, llc>
 * @version   $Id: MY_Controller.php $
 */

abstract class MY_Controller extends Controller
{

    public function __construct()
    {
        // load the parent controller
        parent::Controller();

        /**
         * Very basic permission system. Each element represents a controller/method and the associated
         * minimum permission level necessary to access that resource. The higher the user number, the
         * more permission that user has. Lowest user level (3) can only modify their own listings.
         * For paths not in this list, the user must be super admin.
         * 
         * 1 = Super admin
         * 2 = Admin
         * 3 = Constituent
         * 4 = Account user
         */

        //Localize path variables
        //$userLevel = $this->session->userdata('user_type');
        $mainPath = $this->router->class . "/" . $this->router->method;
        
        //Don't bother for login controller
        if($this->router->class == 'login'){
            return;
        }
        
        //Remove trailing /
        if(substr($this->router->uri->uri_string, -1) == '/'){
            $currentPath = substr($this->router->uri->uri_string, 0, -1);
        }else{
            $currentPath = $this->router->uri->uri_string;
        }
        
        $this->user = new User();
        
        $userPermissions = array();
        
        //Be sure there are permissions set, if not, then return it
        if(!count($userPermissions)){
            return;
        }
        
        $this->load->helper('url');
        
        //Special user level exceptions
        //If the user as a super admin...don't even bother
        if($userLevel == 1){
            return;
        }
        
        //Account user shouldn't be here
        if($userLevel == 4){
              //Level 4 never has access
              $data['errors'] = array('You do not have permission to access this page');
              $this->session->set_flashdata('warn', 'You do not have permission to access this page.');
              $this->load->view('admin/login', $data);
              @$this->session->sess_destroy();
              return;
        }
        
        //Constituent has very limited abilities
        if($userLevel == 3){
            //Set the property list filter
            $this->session->set_userdata('property_filter', array('created_by_user_id'=>$this->session->userdata('user_id')));
            
            //Level 3 users should only ever see/be on stay
            if($currentPath == '/admin/property'){
                //Remember any flash data befor the second redirect
                $this->session->set_flashdata('warn', $this->session->flashdata('warn'));
                redirect('/admin/property/stay', 'refresh');
            }
            
            //If they are tying to edit/delete, they can only do it to their own properties
            if(($mainPath == 'property/edit') || ($mainPath == 'property/delete')){
                $this->load->model('property_model', 'property');
                
                //Query the property in question to be sure it is owned by the user
                $this->property->db->select('id');
                $this->property->db->from('property');
                $this->property->db->where('id', $this->router->uri->segments['4']);
                $this->property->db->where('created_by_user_id', $this->session->userdata('user_id'));
                
                if(!$this->property->db->get()->num_rows){
                    $this->session->set_flashdata('warn', "You do not have permission to edit this listing");
                    redirect('/admin/property/stay', 'refresh');
                }
            }
        }
       
        //Check if key exists and the user has the right level. If path is not found then user must be super user
        //Check the explicit current path for page specific overrides and permission level
        if(array_key_exists($currentPath, $userPermissions) && ($userLevel > $userPermissions[$currentPath])){error_log('in first');
            $this->session->set_flashdata('warn', 'You do not have permission to access that page.');
            redirect('/admin/property/', 'refresh');
        }elseif(!array_key_exists($currentPath, $userPermissions) && !array_key_exists('/admin/' . $mainPath, $userPermissions) && ($userLevel != 1)){
            //Otherwise, if the current path isn't set AND the main path isn't set AND they are not a super admin...then deny them.
            $this->session->set_flashdata('warn', 'You do not have permission to access that page.');
//error_log($currentPath . " | " . $mainPath);
            redirect('/admin/property/', 'refresh');
        }
    }
    
    /*public function Controller(){
        parent::Controller();
    }*/


    private $_initialized_image = false;
    public function _init_image()
    {
        define('MEDIA_PATH', './images/assets/');
        define('DOCUMENT_PATH', './documents/');
        define('UPLOAD_PATH', './uploads/');

        // load the image file upload helper
        $this->load->helper('image');

        // set the initialization complete
        $this->_initialized_image = true;
    }



    /**
     * saves the image to the media directory
     *
     * @return
     */
    public function _save_image($index = false, $scale_width = 1, $prefix = FALSE, $image_path = FALSE)
    {
        $index = ($index && $index != '') ? '_'.$index : '';

        // get the form variables for the uploaded file and crop area
        $x1 = $this->input->post("x1".$index);
        $y1 = $this->input->post("y1".$index);
        $x2 = $this->input->post("x2".$index);
        $y2 = $this->input->post("y2".$index);
        $w = $this->input->post("w".$index);
        $h = $this->input->post("h".$index);
        $uploaded_file = $this->input->post('uploaded_file'.$index);

        // get the original file and move it to the new media path
        $image_name = time(); // set the image name as the timestamp so it's unique
        $ext = find_extension($uploaded_file);
        $ext = ($ext) ? '.'.$ext : '.jpg';

        // make sure the path is created for the image
        if(!file_exists(MEDIA_PATH.$image_path)) {
            mkdir(MEDIA_PATH.$image_path, 0775, true);
        }

        // set the new image name
        $image['original_image'] = (($image_path) ? $image_path.'/' : '').$image_name.'_'.$index.$ext;
        $image['image'] = $image_name.'_'.$index.$ext;

        // move the uploaded file into the correct directory
        @copy('.'.$uploaded_file, MEDIA_PATH.$image['original_image']);
		log_message('INFO', MEDIA_PATH.$image['original_image']);

        // create the original image
        $original_image = basename(MEDIA_PATH.$image['original_image']);
        $large_image_location = MEDIA_PATH.$image['original_image'];

        if(is_array($scale_width))
        {
            foreach($scale_width as $new_image)
            {
                //Scale the image to the thumb_width
                $scale = (!isset($new_image['scale_width'])) ? 1 : ($new_image['scale_width'] / $w);

                // create the sized image off the original image
                $image_name = $new_image['prefix'].'_'.$original_image;
                $image_location = str_replace($original_image, $image_name, MEDIA_PATH.$image['original_image']);

                // crop the image
                resizeThumbnailImage($image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
                $image[$new_image['prefix']] = str_replace($original_image, $image_name, $image['original_image']);
            }

            return $image;
        }
        else
        {
            //Scale the image to the thumb_width set above
            $scale = ($scale_width == 1) ? $scale_width : ($scale_width / $w);

            // create the thumb information off of the original image
            $crop_image = ($prefix ? $prefix.'_' : 'crop_').$original_image;
            $crop_image_location = str_replace($original_image, $crop_image, MEDIA_PATH.$image['original_image']);

            // crop the image
            $cropped = resizeThumbnailImage($crop_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);

            $image['crop'] = str_replace($original_image, $crop_image, $image['image']);

            return $image['crop'];
        }
    } // end _save_image


    /**
     * saves the file to the document directory
     *
     * @return
     */
    public function _save_file($index = 'document', $id = false)
    {
        $index = ($index && $index != '') ? '_'.$index : '';

        // get the form variables for the uploaded file
        $uploaded_file = $this->input->post('uploaded_file'.$index);

        // get the original file and move it to the new media path
        $file_name = $id; // set the image name as the timestamp so it's unique
        $ext = find_extension($uploaded_file);
        $ext = ($ext) ? '.'.$ext : '.pdf';

        // make sure the path is created for the image
        if(!file_exists(DOCUMENT_PATH)) {
            mkdir(DOCUMENT_PATH, 0775, true);
        }

        // set the new image name
        $file['original_file'] = $uploaded_file;
        $file['filename'] = $file_name.$ext;

        // move the uploaded file into the correct directory
        @copy('.'.$uploaded_file, DOCUMENT_PATH.$file['filename']);
		log_message('INFO', DOCUMENT_PATH.$file['filename']);

		return $file['filename'];
    } // end _save_file

}

