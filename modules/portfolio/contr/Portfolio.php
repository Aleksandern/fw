<?php
namespace modules\portfolio\contr;

use \app\core\module\contr\Module;

class Portfolio extends Module 
{

    /*function __construct($module)
    {
        $this->model = new model_portfolio();
        $this->view = new view();
    $this->config = config::get();
    $this->module = $module;
        
    }*/
    
    public function actionIndex()
    {
        //$data = $this->model->get_data();		
        //$this->view->generate($this->module, $data);
        $get_data = $this->model->getData();
        $data = '';
        foreach ($get_data as $key => $val) {
            $data .= $val['Year'].' - '.$val['Site'].' - '.$val['Description'].'<br>';
        }
        $this->tmpl->port = $data;
    }
}
