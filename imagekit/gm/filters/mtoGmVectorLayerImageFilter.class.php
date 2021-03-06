<?php

mtoClass :: import("mtokit/imagekit/mtoAbstractImageFilter.class.php");


class mtoGmVectorLayerImageFilter extends mtoAbstractImageFilter
{

  function apply(mtoAbstractImageContainer $container)
  {
    $width = $container->getWidth();
    $height = $container->getHeight();

    $file = $this->getParam('file');
    $color = $this->getParam('color');

    $wm_cont = new Gmagick();
    $wm_cont->readImage($file);
    $wm_cont ->setImageMatte( true );

    //$color_pix = new ImagickPixel('transparent');
    $it = $wm_cont->getPixelIterator();
    foreach( $it as $row => $pixels )
    {
        foreach ( $pixels as $column => $pixel )
        {
            //if(!$pixel->isSimilar($color_pix,1))
            if ($pixel->getColorValue (Gmagick::COLOR_ALPHA) != 0)
            {
                $op = $pixel->getColorValue(Gmagick::COLOR_OPACITY);
                $pixel->setColor($color);
                $pixel->setColorValue(Gmagick::COLOR_OPACITY,$op);
            }

            //var_dump();

        }
    $it->syncIterator();
    }

    
    $angle = (int)$this->getParam('rotate');
    $v_ref = (int)$this->getParam('v_reflect');
    $h_ref = (int)$this->getParam('h_reflect');

    if ($v_ref > 0)
    {
        $wm_cont->flipImage();
    }
    if ($h_ref > 0)
    {
        $wm_cont->flopImage();
    }
    if ($angle !== 0)
    {
        $wm_cont->rotateImage(new GmagickPixel('transparent'), $angle);
    }

    $wm_cont->thumbnailImage($this->getParam('width'),$this->getParam('height'),1);

    $container->getResource()->compositeImage($wm_cont, Gmagick::COMPOSITE_OVER, $this->getParam('x'), $this->getParam('y'));
    //$container->getResource()->enhanceImage();
  }

  
}
