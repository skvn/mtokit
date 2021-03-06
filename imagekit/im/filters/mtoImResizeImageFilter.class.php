<?php

mtoClass :: import("mtokit/imagekit/mtoAbstractImageFilter.class.php");

class mtoImResizeImageFilter extends mtoAbstractImageFilter
{

    function apply(mtoAbstractImageContainer $container)
    {
        $image = $container->getResource();
        if ($image instanceof Imagick)
        {
            if (!is_numeric($this->getWidth()) || !is_numeric($this->getHeight()))
            {
                mtoProfiler :: instance()->logDebug($_SERVER['REQUEST_URI'] . "\t" . $_SERVER['HTTP_REFERER'], "debug/cache_invalid_size");
            }

            if ($this->getPreserveAspectRatio())
            {

                //new size
                $new_size = $this->calcNewSize($image->getImageWidth(), $image->getImageHeight());
                $image->scaleImage($new_size[0], $new_size[1], false);

            } else {


                if ($this->getClip()) {
                    $image->scaleImage($this->getWidth(), $this->getHeight(), false);
                } else {
                    $image->scaleImage($this->getWidth(), $this->getHeight(), true);
                }
            }
        }
        if ($this->getJpegQuality())
        {
            $image->setImageFormat('jpeg');
            $image->setImageCompressionQuality($this->getJpegQuality());
        }
        $container->replaceResource($image);
    }

    protected function calcNewSize($src_w, $src_h)
    {
        $dst_w = $this->getWidth();
        if (!$dst_w)
            $dst_w = $src_w;
        $dst_h = $this->getHeight();
        if (!$dst_h)
            $dst_h = $src_h;

        return $this->calcSize($src_w, $src_h, $dst_w, $dst_h, $this->getPreserveAspectRatio(), $this->getSaveMinSize());
    }

    function getJpegQuality()
    {
        return $this->getParam("jpeg_quality");
    }

    function getWidth()
    {
        return $this->getParam('width');
    }

    function getHeight()
    {
        return $this->getParam('height');
    }

    function getPreserveAspectRatio()
    {
        return $this->getParam('preserve_aspect_ratio', false);
    }

    function getSaveMinSize()
    {
        return $this->getParam('save_min_size', false);
    }

    function getClip()
    {
        return $this->getParam("clip");
    }


    function getXxx()
    {
        return $this->getParam('xxx', false);
    }

}
