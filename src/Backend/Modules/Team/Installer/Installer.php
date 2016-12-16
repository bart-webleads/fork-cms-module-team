<?php

namespace Backend\Modules\Team\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the Team module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Installer extends ModuleInstaller
{
    public function install()
    {
        // import the sql
        $this->importSQL(dirname(__FILE__) . '/Data/install.sql');

        // install the module in the database
        $this->addModule('Team');

        // install the locale, this is set here beceause we need the module for this
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        $this->setModuleRights(1, 'Team');

        $this->setActionRights(1, 'Team', 'Add');
        //$this->setActionRights(1, 'Team', 'AddCategory');
        //$this->setActionRights(1, 'Team', 'AddImages');
        $this->setActionRights(1, 'Team', 'Categories');
        $this->setActionRights(1, 'Team', 'Delete');
        $this->setActionRights(1, 'Team', 'DeleteCategory');
        $this->setActionRights(1, 'Team', 'DeleteImage');
        $this->setActionRights(1, 'Team', 'Edit');
        $this->setActionRights(1, 'Team', 'EditCategory');
        $this->setActionRights(1, 'Team', 'Index');

        $this->setActionRights(1, 'Team', 'Sequence');
        $this->setActionRights(1, 'Team', 'SequenceCategories');
        $this->setActionRights(1, 'Team', 'SequenceImages');
        $this->setActionRights(1, 'Team', 'UploadImages');
        $this->setActionRights(1, 'Team', 'EditImage');

        $this->setActionRights(1, 'Team', 'Settings');
        $this->setActionRights(1, 'Team', 'GenerateUrl');
        $this->setActionRights(1, 'Team', 'UploadImage');

        $this->makeSearchable('Team');

        // add extra's
        $subnameID = $this->insertExtra('Team', 'block', 'Team', null, null, 'N', 1000);
        $this->insertExtra('Team', 'block', 'TeamMemberDetail', 'Detail', null, 'N', 1001);

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationModulesId = $this->setNavigation($navigationModulesId, 'Team');
        $this->setNavigation($navigationModulesId, 'Team', 'team/index', array('team/add','team/edit', 'team/index', 'team/add_images', 'team/edit_image'), 1);
        $this->setNavigation($navigationModulesId, 'Categories', 'team/categories', array('team/add_category','team/edit_category', 'team/categories'), 2);

         // settings navigation
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Team', 'team/settings');
    }
}
