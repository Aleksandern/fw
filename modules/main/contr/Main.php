<?php
namespace modules\main\contr;

use \app\core\module\contr\Module;

class Main extends Module 
{
    public function actionIndex() 
    {	
      //$view = new tmpl_vi();
      //$file = site_path.DIRSEP.'module'.DIRSEP.'main'.DIRSEP.'view.php';
      //$view->setfile($file);  
      //$view->addvar('view2','123');
      //$view->addvar($); $this->module
      //$this->view->setfile($file);  
      //echo 'asd';

      //$this->view->addvar('vieww', $view);
      //$this->view->display();
      //return '12';
        $this->tmpl->eprstloc = 'eprst1';
        $this->tmpl_main->eprst = 'eprst1';
        //echo $this->tmpl->lang_m_hzhz;

    }
}

