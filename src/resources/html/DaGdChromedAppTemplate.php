<?php

class DaGdChromedAppTemplate extends DaGdAppTemplate {
  public function getStyle() {
    $style = <<<EOD
#bar {
  padding: 15px 0;
  height: 65px;
  line-height: 65px;
  border-bottom: 1px solid #eee;
  font-family: "Proxima Nova", overpass, Ubuntu, sans-serif !important;
  font-weight: 300;
  font-size: 1.7em;
  margin-bottom: 10px;
}
.constraint { width: 85%; margin: 0 auto; max-width: 1080px; }
#bar a, #bar a:active, #bar a:visited { color: #ccc; }
table { border-spacing: 0 30px; border-collapse: separate; }
td { padding: 10px 0; }
input[type=text] { border: 1px solid #ccc; }
.sitename { color: #333; float: left; font-weight: 500; }
.lightmode .sitename { color: #3a9; }
.darkmode .sitename { color: #39a; }
.appname { color: #888; }
.darkmode #bar { border-color: #555; }
.flex-1 { flex: 1; }
.ml5 { margin-left: 5px; }
.mr5 { margin-right: 5px; }
.mt10 { margin-top: 10px; }
.mb10 { margin-bottom: 10px; }
.ow-bw { overflow-wrap: break-word; }
body.darkmode { background-color: #333; color: #ddd; }
body.lightmode { background-color: #f6f5f4; }
body.darkmode a, body.darkmode a:active, body.darkmode a:visited { color: #ccc; }
body, .sitename { margin: 0; padding: 0; }
#app { font-family: "Proxima Nova", overpass, Ubuntu, sans-serif; clear: both; box-sizing: border-box; }
.card {
  box-shadow: 1px 1px 2px #555;
}
.card .card-title, .card .card-body, .card .card-footer { padding: 10px; }
body.lightmode .card { background-color: #fff; }
.card .card-title { background-color: #9C89B8; color: #422040; }
body.darkmode .card { background-color: #888; }
EOD;
    return array_merge(parent::getStyle(), array($style));
  }

  protected function getLinksArray() {
    $darkmode_link = tag(
      'a',
      $this->getDarkmode() ? 'light mode' : 'dark mode',
      array(
        'href' => $this->getDarkmode() ? '?darkmode=0' : '?darkmode=1',
      )
    );

    $links = array(
      tag('a', 'help', array('href' => '/help')),
      tag(
        'a',
        'open source',
        array('href' => 'https://github.com/dagd/dagd')
      ),
      $darkmode_link,
      tag('a', 'donate', array('href' => 'https://www.patreon.com/relrod')),
    );

    return $links;
  }

  protected function getSiteName() {
    $out = array();
    $out[] = tag(
      'a',
      tag('span', 'dagd', array('class' => 'sitename')),
      array(
        'href' => '/',
      )
    );
    $title = $this->getTitle();
    if ($title) {
      $out[] = tag('span', ':'.$title, array('class' => 'appname'));
    }
    return $out;
  }

  protected function getChrome() {
    $sitename = $this->getSiteName();
    $links = intersperse(' / ', $this->getLinksArray());
    $links_div = tag(
      'div',
      $links,
      array(
        'style' => 'float: right; display: inline;',
      )
    );

    $navbar = tag(
      'div',
      array_merge($sitename, array($links_div)),
      array(
        'class' => 'constraint',
      )
    );

    $navbar_container = tag(
      'div',
      $navbar,
      array(
        'id' => 'bar',
      )
    );

    $constrainted_body = tag(
      'div',
      parent::getBody(),
      array(
        'id' => 'app',
        'class' => 'constraint',
      ),
      !$this->getEscape() // TODO: Nix this when we can
    );

    return tag(
      'div',
      array(
        $navbar_container,
        $constrainted_body,
      )
    );
  }

  public function getBody() {
    return $this->getChrome();
  }

}
