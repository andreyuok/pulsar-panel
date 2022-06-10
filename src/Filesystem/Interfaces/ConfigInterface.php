<?php
namespace App\Filesystem\Interfaces;

interface ConfigInterface
{

    /** PATH to Dirs */
    const ABS_PATH_TO_SITES_AVAILABLE = '/etc/apache2/sites-available/';
    const ABS_PATH_TO_SITES_ENABLED = '/etc/apache2/sites-enabled/';

    /** String to search */
    const VHOST_DOCUMENT_ROOT_NODE = 'DocumentRoot';

}
