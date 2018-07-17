<?php
/**
 * Navigation plugin for Craft CMS 3.x
 *
 * Craft navigation for the website.
 *
 * @link      https://fatfish.com.au
 * @copyright Copyright (c) 2018 Fatfish
 */

namespace fatfish\navigation\models;

use fatfish\navigation\Navigation;

use Craft;
use craft\base\Model;

/**
 * NavigationModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Fatfish
 * @package   Navigation
 * @since     1.0.0
 */
class NavigationModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    public $someAttribute = 'Some Default';
    public $siteId;
    public $MenuName;
    public $MenuHtml;



    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
                    ['siteId','required'],
                    ['someAttribute', 'default', 'value' => 'Some Default'],
                    ['MenuName','required'],
                    ['MenuHtml','required']
            ];
    }
}
