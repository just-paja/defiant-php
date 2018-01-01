<?php

namespace Defiant\View;

class ServerError extends \Defiant\View {
  public function __construct(\Defiant\Runner $runner = null, \Defiant\Http\Request $request = null) {
    parent::__construct($runner, $request);
    $this->templatesPath = realpath(__DIR__.'/../../templates');
  }

  public function notFound() {
    $this->status = 404;
    return $this->renderTemplate($this->templatesPath.'/404.html', [], true);
  }

  public function badRequest(array $context = []) {
    $this->status = 400;
    return $this->renderTemplate($this->templatesPath.'/400.html', $context, true);
  }

  public function fatalError(array $context = []) {
    $this->status = 500;
    return $this->renderTemplate($this->templatesPath.'/500.html', $context, true);
  }
}