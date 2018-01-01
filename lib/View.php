<?php

namespace Defiant;

class View {
  protected $headers = [];
  protected $host;
  protected $models;
  protected $params = [];
  protected $path;
  protected $protocol;
  protected $request;
  protected $runner;
  protected $status = 200;

  public function __construct(Runner $runner = null, Http\Request $request = null) {
    if ($runner) {
      $this->runner = $runner;
      $this->models = $runner->models;
    }
    if ($request) {
      $this->host = $request->host;
      $this->params = $request->getParams();
      $this->path = $request->path;
      $this->protocol = $request->protocol;
      $this->request = $request;
    }
  }

  public function addHeader($header, $value) {
    $this->headers[$header] = $value;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function getParam($name) {
    return isset($this->params[$name]) ? $this->params[$name] : null;
  }

  public function getStatus() {
    return $this->status;
  }

  public function isAccessible() {
    return true;
  }

  public function renderTemplate($template, array $context = []) {
    $nameSplit = explode('.', $template);
    $suffix = $nameSplit[sizeof($nameSplit) - 1];

    if ($renderer = $this->runner->getRenderer($suffix)) {
      if (!($renderer instanceof \Defiant\View\Renderer)) {
        throw new Error(sprintf('Renderer %s does not inherit from Defiant\\View\\Renderer', get_class($renderer)));
      }
      $context['request'] = $this->request;
      $context['router'] = $this->runner->getRouter();
      return $renderer->renderFile($template, $context);
    }

    throw new Error(sprintf('Renderer for suffix %s is not configured!', $suffix));
  }

  public function url($path, array $params = []) {
    $this->runner->getRouter()->getUrl($path, $params);
  }
}
