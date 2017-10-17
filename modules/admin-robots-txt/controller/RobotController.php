<?php
/**
 * Robots file management
 * @package admin-robots-txt
 * @version 0.0.1
 * @upgrade true
 */

namespace AdminRobotsTxt\Controller;

class RobotController extends \AdminController
{
    private function _defaultParams(){
        return [
            'title'             => 'Robots.txt',
            'nav_title'         => 'Setting',
            'active_menu'       => 'setting',
            'active_submenu'    => 'robots-txt'
        ];
    }
    
    public function disableAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->manage_robots_txt)
            return $this->show404();
            
        $robots_file = BASEPATH . '/robots.txt';
        if(!is_file($robots_file))
            return $this->redirectUrl('adminSettingRobots');
        
        $trash_file = BASEPATH . '/robots.txt~';
        rename($robots_file, $trash_file);
        
        return $this->redirectUrl('adminSettingRobots');
    }
    
    public function disabledAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->manage_robots_txt)
            return $this->show404();
        
        $robots_file = BASEPATH . '/robots.txt';
        if(is_file($robots_file))
            return $this->redirectUrl('adminSettingRobots');
        
        $params = $this->_defaultParams();
        
        return $this->respond('setting/robots/disabled', $params);
    }
    
    public function enableAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->manage_robots_txt)
            return $this->show404();
        
        $robots_file = BASEPATH . '/robots.txt';
        if(is_file($robots_file))
            return $this->redirectUrl('adminSettingRobots');
        
        $trash_file = BASEPATH . '/robots.txt~';
        if(is_file($trash_file)){
            rename($trash_file, $robots_file);
        }else{
            $nl = PHP_EOL;
            
            $f  = fopen($robots_file, 'w');
            
            $tx = 'User-agent: *' . $nl;
            $tx.= 'Disallow: /admin/' . $nl;
            $tx.= 'Disallow: /comp/' . $nl;
            $tx.= 'Disallow: /plugins/feedback.php' . $nl;
            $tx.= $nl;
            $tx.= 'User-agent: Alexabot' . $nl;
            $tx.= 'Disallow: /post/amp/' . $nl;
            if(module_exists('robot')){
                $tx.= $nl;
                $tx.= 'Sitemap: ' . $this->router->to('robotSitemap');
            }
            
            fwrite($f, $tx);
            fclose($f);
        }
        
        return $this->redirectUrl('adminSettingRobots');
    }
    
    public function editAction(){
        if(!$this->user->login)
            return $this->loginFirst('adminLogin');
        if(!$this->can_i->manage_robots_txt)
            return $this->show404();
        
        $robots_file = BASEPATH . '/robots.txt';
        if(!is_file($robots_file))
            return $this->redirectUrl('adminSettingRobotsDisabled');
        
        $params = $this->_defaultParams();
        $params['success'] = false;
        
        $object = (object)[
            'text' => file_get_contents($robots_file)
        ];
        
        if(false === ($form = $this->form->validate('admin-robot-file', $object)))
            return $this->respond('setting/robots/edit', $params);
        
        $f = fopen($robots_file, 'w');
        fwrite($f, $form->text);
        fclose($f);
        
        $params['success'] = true;
        return $this->respond('setting/robots/edit', $params);
    }
}