<?php

namespace Frontend\Modules\Team\Widgets;


use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Team\Engine\Model as FrontendTeamModel;


class Category extends FrontendBaseWidget
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
        if(isset($this->data['id'])) {
            $filter['categories'][] = $this->data['id'];
            $this->tpl->assign('widgetTeamCategory', FrontendTeamModel::getAll(3,0,$filter));
        }

    }
}
