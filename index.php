<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>The Brave Hero</title>
    </head>
    <body>
        <h1>The Brave Hero</h1>
<?php
        class gameRecorder {
        private static $instance;
  
        public static function get() {
             if (is_null(self::$instance)) {
             self::$instance = new self();
    }
    return self::$instance;
  }
  
  public function record($msg) {
    echo $msg;
  }
}

abstract class Jucator{
  public 
   $name,
   $viata,
   $putere,
   $aparare,
   $viteza,
   $noroc;
    
  public $runde;
  public $recorder;
  
  public static function pregateste($jucator, $name) {
   $className = ucfirst(strtolower($jucator));
    return new $className($name);
  }
  
  public function __construct($name) {
    $this->name = $name;
   
    $this->Eroul();
    $this->Bestia();
  }
  
  public function esteInvins() {
    return !$this->esteInViata() || !$this->areRundeRamase();
  }
  
  public function esteInViata() {
    return $this->viata > 0;
  }
  
  public function areRundeRamase() {
    return $this->runde > 0;
  }
  
  public function evitaAtacul() {
    return $this->noroc == mt_rand(0, 100) / 100;
  }
  
  public abstract function ataca(Jucator $w);
  public abstract function apara($putere);
  
  protected abstract function Eroul();
  protected abstract function Bestia();
}

class Game extends Jucator {
    
  public function ataca(Jucator $w) {
    --$this->runde;
    
    if($this->runde){
        
    }
    $damage = $this->putere * ((0.1 == mt_rand(1,100) / 100) ? 2 : 1);
    
    $this->recorder->record('Ataca ' . $this->name . ' si reuseste ' . $damage . ' damage.<br /><br />');
    
    $w->apara($damage);
  }
  
  public function apara($putere) {
    if ($this->evitaAtacul()) {
      $this->recorder->record($this->name . ' a reusit sa evite atacul.<br />');
      return;
    }
    $this->recorder->record($this->name . ' are ' . $this->viata . ' viata.<br/>');
    
    $damage = $putere - $this->aparare;
    if ($damage < 0)
    {
        $damage=0;
    }
    elseif ($damage > 100) {
    $damage = 100;
    } else {
      $this->viata -= $damage;
    }
    if($this->viata < 0){
        return $this->viata = 0;
    }
    $this->recorder->record($this->name . ' s-a aparat, dar a pierdut ' . $damage . ' din viata.<br />');
    $this->recorder->record($this->name . ' mai are '. $this->viata . ' din viata.<br />');
  }
  
  protected function Eroul() {
    $this->viata = mt_rand(65, 95);
    $this->putere = mt_rand(60, 70);
    $this->aparare = mt_rand(40, 50);
    $this->viteza = mt_rand(40, 50);
    $this->noroc = mt_rand(10, 30) / 100;
  }
  protected function Bestia() {
    $this->viata = mt_rand(55, 80);
    $this->putere = mt_rand(50, 80);
    $this->aparare = mt_rand(35, 55);
    $this->viteza = mt_rand(40, 60);
    $this->noroc = mt_rand(25, 40) / 100;
  }
}

class PadureaFermecata
{
  public static function lupta(Jucator $j1, Jucator $j2) {
    $j1->runde = 20;
    $j1->recorder = gameRecorder::get();
    
    $j2->runde = 20;
    $j2->recorder = gameRecorder::get();
    
    list($j1, $j2) = self::incepeLupta($j1, $j2);
    while (!$j1->esteInvins() && !$j2->esteInvins()) {
      $j1->ataca($j2);
      $j2->ataca($j1);
    }
    
    if ($j1->esteInViata() && $j2->esteInViata()){
    gameRecorder::get()->record('La egalitate');
    }elseif ($j1->esteInViata())
    {
    gameRecorder::get()->record($j1->name . ' a castigat!<br />');
    }else{
    gameRecorder::get()->record($j2->name . ' a castigat!<br />');}
  }
  
  protected static function incepeLupta(Jucator $j1, Jucator $j2) {
    $r = array($j1, $j2);
    if ($j1->viteza > $j2->viteza) {
      $j1->ataca($j2);
      $r = array($j2, $j1);
    } else if ($j1->viteza == $j2->viteza) {
      if ($j1->noroc > $j2->noroc) {
        $j1->ataca($j2);
        $r = array($j2, $j1);
      } else {
        $j2->ataca($j1);
      }
    } else {
      $j2->ataca($j1);
    }
    return $r;
  }
}
PadureaFermecata::lupta(
  Jucator::pregateste('Game', 'Carl'),
  Jucator::pregateste('Game', 'Beast')
);
        ?>
    </body>
</html>
