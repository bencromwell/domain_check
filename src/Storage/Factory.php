<?php

namespace DomainCheck\Storage;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class Factory
{
    public function storage(): Storage
    {
        return new Storage(new Filesystem(new Local(Storage::ROOT)));
    }
}
