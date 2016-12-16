<?php

namespace Frontend\Modules\Team\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Team\Engine\Model as FrontendTeamModel;
use Frontend\Modules\Team\Engine\Categories as FrontendTeamCategoriesModel;

class CategoriesNavigation extends FrontendBaseWidget
{
    /**
     * Execute the extra
     */
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();
        $this->parse();
    }

    /**
     * Parse
     */
    private function parse()
    {
        $this->tpl->assign('widgetTeamCategoriesNavigation', FrontendTeamCategoriesModel::getAll(array('parent_id' => 0)));
    }
}
