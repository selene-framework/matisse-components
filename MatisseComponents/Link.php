<?php
namespace Selenia\Plugins\MatisseComponents;

use Selenia\Interfaces\Navigation\NavigationLinkInterface;
use Selenia\Matisse\Components\Base\HtmlComponent;
use Selenia\Matisse\Properties\Base\HtmlComponentProperties;
use Selenia\Matisse\Properties\TypeSystem\type;

class LinkProperties extends HtmlComponentProperties
{
  /**
   * @var string
   */
  public $action = type::id;
  /**
   * @var bool
   */
  public $active = false;
  /**
   * @var string
   */
  public $activeClass = 'active';
  /**
   * @var string
   */
  public $currentUrl = type::string;
  /**
   * @var bool
   */
  public $disabled = false;
  /**
   * @var string
   */
  public $disabledClass = 'disabled';
  /**
   * @var string
   */
  public $href = type::string;
  /**
   * @var string
   */
  public $icon = '';
  /**
   * @var string
   */
  public $label = '';
  /**
   * @var NavigationLinkInterface
   */
  public $link = type::data;
  /**
   * @var string
   */
  public $param = '';
  /**
   * @var string
   */
  public $script = '';
  /**
   * @var string
   */
  public $tooltip = '';
  /**
   * @var string
   */
  public $wrapper = '';
}

class Link extends HtmlComponent
{
  protected static $propertiesClass = LinkProperties::class;

  /** @var LinkProperties */
  public $props;

  protected $containerTag = 'a';
  protected $disabled;

  protected function preRender ()
  {
    $prop = $this->props;

    if ($link = $prop->link) {
      extend ($prop, [
        'label'  => $link->title (),
        'href'   => $link->url (),
        'icon'   => $link->icon (),
        'active' => $link->isActive (),
      ]);
    }

    $this->disabled = (is_null ($prop->href) && !exists ($prop->script)) || $prop->disabled;
    if ($this->disabled)
      $this->addClass ($prop->disabledClass);

    if ($prop->active || (exists ($prop->href) && str_beginsWith ($prop->currentUrl, $prop->href))
        || $prop->href === '' && $prop->currentUrl === ''
    )
      $this->cssClassName = $prop->activeClass;

    if (!empty($prop->wrapper))
      $this->containerTag = $prop->wrapper;
    parent::preRender ();
  }

  protected function render ()
  {
    $prop = $this->props;

    if (!empty($prop->wrapper))
      $this->begin ('a');

    $script = $prop->action ? "selenia.doAction('$prop->action','$prop->param')"
      : $prop->script;

    if (exists ($script))
      $this->attr ('onclick', $script);

    if (exists ($prop->tooltip))
      $this->attr ('title', $prop->tooltip);

    $this->attr ('href', $this->disabled
      ? 'javascript:void(0)'
      :
      (isset ($prop->href)
        ?
        $prop->href . $this->disabled
        :
        "javascript:void(0)"
      )
    );
    $this->beginContent ();
    if (exists ($prop->icon))
      $this->tag ('i', ['class' => $prop->icon]);
    echo e ($prop->label);

    if (!empty($prop->wrapper))
      $this->end ();
  }
}
