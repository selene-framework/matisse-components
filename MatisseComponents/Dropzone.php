<?php
namespace Selenia\Plugins\MatisseComponents;

use Selenia\Matisse\Components\Base\HtmlComponent;
use Selenia\Matisse\Properties\Base\HtmlComponentProperties;
use Selenia\Matisse\Properties\TypeSystem\type;

class DropzoneProperties extends HtmlComponentProperties
{
  /**
   * @var string
   */
  public $acceptedFiles = '';
  /**
   * @var bool
   */
  public $autoProcessQueue = true;
  /**
   * @var int
   */
  public $maxFiles = type::number;
  /**
   * @var string
   */
  public $method = type::string;
  /**
   * @var int
   */
  public $parallelUploads = type::number;
  /**
   * @var string
   */
  public $url = '';
}

class Dropzone extends HtmlComponent
{
  protected static $propertiesClass = DropzoneProperties::class;

  /** @var bool */
  public $allowsChildren = true;
  /** @var string */
  public $cssClassName = 'dropzone';
  /** @var DropzoneProperties */
  public $props;

  /** @var bool */
  protected $autoId = true;

  protected function init ()
  {
    $this->context->addStylesheet ('lib/dropzone/dist/min/dropzone.min.css');
    $this->context->addScript ('lib/dropzone/dist/min/dropzone.min.js');

    $this->context->addInlineScript ("
Dropzone.autoDiscover = false;
", 'init-dropzone');
  }

  protected function render ()
  {
    $prop = $this->props;

    $this->context->addInlineScript (<<<JS
(function(element){
  var dropzone = new Dropzone (element[0], {
    url:                          '$prop->url',
    /*
    accept:                       getHandler ('onAccept'),
    acceptedFiles:                $prop->acceptedFiles,
    maxFiles:                     $prop->maxFiles,
    clickable:                    clickable,
    addRemoveLinks:               true,
    parallelUploads:              $prop->parallelUploads || 2,
    method:                       $prop->method || 'post',
    autoProcessQueue:             uilib.boolAttr ($prop->autoProcessQueue, true),
    */
    dictDefaultMessage:           "Arraste ficheiros para aqui ou clique para escolher os ficheiros a enviar.",
    dictInvalidFileType:          'Ficheiro inválido',
    dictFileTooBig:               'Ficheiro demasiado grande',
    dictResponseError:            'Erro ao enviar',
    dictCancelUpload:             'Cancelar',
    dictCancelUploadConfirmation: 'Tem a certeza?',
    dictRemoveFile:               'Apagar',
    dictMaxFilesExceeded:         'Não pode inserir mais ficheiros',
  });
})($('#$prop->id'));
JS
    );
  }

}

