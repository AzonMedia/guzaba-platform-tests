<?php
declare(strict_types=1);

namespace GuzabaPlatform\Tests;

use GuzabaPlatform\Components\Base\BaseComponent;
use Guzaba2\Base\Base;
use Guzaba2\Event\Event;
use Guzaba2\Mvc\Controller;
use Guzaba2\Mvc\ExecutorMiddleware;
use Guzaba2\Orm\ActiveRecord;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInitializationInterface;
use GuzabaPlatform\Components\Base\Interfaces\ComponentInterface;
use GuzabaPlatform\Platform\Admin\Controllers\Navigation;
use GuzabaPlatform\Platform\Application\Middlewares;
use GuzabaPlatform\RequestCaching\Hooks\AdminEntry;

/**
 * Class Component
 * @package GuzabaPlatform\Tests
 */
class Component extends BaseComponent implements ComponentInterface
{
    protected const COMPONENT_NAME = "Guzaba Platform Tests";
    //https://components.platform.guzaba.org/component/{vendor}/{component}
    protected const COMPONENT_URL = 'https://components.platform.guzaba.org/component/guzaba-platform/guzaba-platform-tests';
    //protected const DEV_COMPONENT_URL//this should come from composer.json
    protected const COMPONENT_NAMESPACE = __NAMESPACE__;
    protected const COMPONENT_VERSION = '0.0.1';//TODO update this to come from the Composer.json file of the component
    protected const VENDOR_NAME = 'Azonmedia';
    protected const VENDOR_URL = 'https://azonmedia.com';
    protected const ERROR_REFERENCE_URL = 'https://github.com/AzonMedia/guzaba-platform-tests/tree/master/docs/ErrorReference/';
}