<?php

final class DaGdRequest {
  private $cookies = array();
  private $request = array();
  private $server = array();
  private $route_matches = array();

  public function setCookies($cookies) {
    $this->cookies = $cookies;
    return $this;
  }

  public function getCookies() {
    return $this->cookies;
  }

  public function getCookie($cookie, $default = null) {
    return idx($this->cookies, $cookie, $default);
  }

  public function setRequest($request) {
    $this->request = $request;
    return $this;
  }

  public function getRequest() {
    return $this->request;
  }

  public function setServer($server) {
    $this->server = $server;
    return $this;
  }

  public function getServer() {
    return $this->server;
  }

  public function setRouteMatches($route_matches) {
    $this->route_matches = $route_matches;
    return $this;
  }

  public function getRouteMatches() {
    return $this->route_matches;
  }

  public function getRouteComponent($idx) {
    return idx($this->route_matches, $idx);
  }

  public function getParamOrDefault(
    $key,
    $default = null,
    $allow_empty = false,
    $empty_default = null) {

    $raw_request = $this->getRequest();
    if ($allow_empty &&
        array_key_exists($key, $raw_request) &&
        strlen($raw_request[$key]) == 0) {
      return $empty_default;
    }
    return idx($raw_request, $key, $default);
  }

  public function getHeader($header) {
    $header = strtoupper($header);
    $header = str_replace('-', '_', $header);
    $header = 'HTTP_'.$header;
    return idx($this->server, $header);
  }

  public function wantsCow() {
    return $this->getParamOrDefault('cow', false, true, true);
  }

  public function wantsText() {
    $text = $this->getParamOrDefault('text', null, true, true);
    if ($text !== null) {
      return $text;
    }

    if ($accept = $this->getHeader('Accept')) {
      $accept = strtolower(str_replace(' ', '', $accept));
      $html_accept_regex = implode('|', DaGdConfig::get('general.html_accept'));
      return !preg_match('#(?:'.$html_accept_regex.')#i', $accept);
    }

    // If all else fails, cater to simple clients and assume text.
    return true;
  }

  public function wantsJson() {
    return $this->getParamOrDefault('json', false, true, true);
  }

  public function getClientIP() {
    if ($this->getHeader('x-dagd-proxy') &&
        $ip = $this->getHeader('X-Forwarded-For')) {
      return $ip;
    }
    return idx($this->server, 'REMOTE_ADDR');
  }
}
