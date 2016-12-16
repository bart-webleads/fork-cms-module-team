<?php

namespace Backend\Modules\Team\Actions;

use Backend\Core\Engine\Base\ActionAdd;
use Backend\Core\Engine\Form;
use Backend\Core\Language\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\Team\Engine\Model as BackendTeamModel;
use Backend\Modules\Team\Engine\Category as BackendTeamCategoryModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;

use Backend\Modules\SiteHelpers\Engine\Helper as SiteHelpersHelper;
use Backend\Modules\SiteHelpers\Engine\Model as SiteHelpersModel;
use Backend\Modules\SiteHelpers\Engine\Assets as SiteHelpersAssets;
use Common\Uri as CommonUri;
use Backend\Core\Engine\Authentication;

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Add extends ActionAdd
{
    /**
     * Execute the actions
     */
    public function execute()
    {
        parent::execute();

        $this->languages = SiteHelpersHelper::getActiveLanguages();
        //SiteHelpersAssets::addSelect2($this->header);


        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    protected function loadForm()
    {
        $this->frm = new Form('add');

        $this->frm->addImage('image');

        // set hidden values
        $rbtHiddenValues[] = array('label' => Language::lbl('Hidden', $this->URL->getModule()), 'value' => 'Y');
        $rbtHiddenValues[] = array('label' => Language::lbl('Published'), 'value' => 'N');

        $this->frm->addRadiobutton('hidden', $rbtHiddenValues, 'N');

        $this->frm->addDate('publish_on_date');
        $this->frm->addTime('publish_on_time');

        // set size values
        $rbtSizeValues[] = array('label' => Language::getLabel('Small'), 'value' => 'small');
        $rbtSizeValues[] = array('label' => Language::getLabel('Medium'), 'value' => 'medium');
        $rbtSizeValues[] = array('label' => Language::getLabel('Large'), 'value' => 'large');
        $this->frm->addRadiobutton('size', $rbtSizeValues);

        //$this->categories = BackendTeamCategoryModel::getForDropdown();
        //$this->frm->addDropdown('categories', $this->categories, null, true, 'select select2', 'selectError select2');

        $this->frm->addText('first_name');
        $this->frm->addText('last_name');
        $this->frm->addText('twitter_site_name');
        $this->frm->addText('instagram_site_name');
        $this->frm->addText('pinterest_site_name');
        $this->frm->addText('facebook_site_name');
        $this->frm->addText('email');
        $this->frm->addText('phone');
        $this->frm->addText('linkedin_url');

       $this->categories = BackendTeamCategoryModel::getForMultiCheckbox();
       if(!empty($this->categories) && Authentication::isAllowedAction('AddCategory')) $this->frm->addMultiCheckbox('categories', $this->categories);

        foreach($this->languages as &$language)
        {
            $field = $this->frm->addText('function_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['function']) ? $this->record['content'][$language['abbreviation']]['function'] : '', null, 'form-control', 'form-control danger');
            $language['function_field'] = $field->parse();


            $field = $this->frm->addEditor('description_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['description']) ? $this->record['content'][$language['abbreviation']]['description'] : '');
            $language['description_field'] = $field->parse();

            $field = $this->frm->addCheckbox('seo_url_overwrite_'. $language['abbreviation']);
            $language['seo_url_overwrite_field'] = $field->parse();

            $field = $this->frm->addCheckbox('seo_description_overwrite_'. $language['abbreviation']);
            $language['seo_description_overwrite_field'] = $field->parse();

            $field = $this->frm->addCheckbox('seo_title_overwrite_'. $language['abbreviation']);
            $language['seo_title_overwrite_field'] = $field->parse();

            $field = $this->frm->addText('url_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['url']) ? $this->record['content'][$language['abbreviation']]['url'] : '');
            $language['url_field'] = $field->parse();

            $field = $this->frm->addText('seo_title_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['seo_title']) ? $this->record['content'][$language['abbreviation']]['seo_title'] : '');
            $language['seo_title_field'] = $field->parse();

            $field = $this->frm->addText('seo_description_'. $language['abbreviation'], isset($this->record['content'][$language['abbreviation']]['seo_description']) ? $this->record['content'][$language['abbreviation']]['seo_description'] : '');
            $language['seo_description_field'] = $field->parse();

            $url = Model::getURLForBlock($this->URL->getModule(), 'Detail',  $language['abbreviation']);
            $url404 = Model::getURL(404,  $language['abbreviation']);
            $language['slug'] = '';
            if($url404 != $url) $language['url'] = SITE_URL . $url;
        }
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();


        $this->tpl->assign('languages', $this->languages);
    }

    /**
     * Validate the form
     */
    protected function validateForm()
    {
        if ($this->frm->isSubmitted()) {

            // get the status
            $status = \SpoonFilter::getPostValue('status', array('active', 'draft'), 'active');

            $this->frm->cleanupFields();

            // validation
            $fields = $this->frm->getFields();

            SiteHelpersHelper::validateImage($this->frm, 'image');

            $this->frm->getField('publish_on_date')->isValid(Language::err('DateIsInvalid'));
            $this->frm->getField('publish_on_time')->isValid(Language::err('TimeIsInvalid'));

            $this->frm->getField('first_name')->isFilled(Language::getError('FieldIsRequired'));
            $this->frm->getField('last_name')->isFilled(Language::getError('FieldIsRequired'));

            if($this->frm->getField('linkedin_url')->isFilled()) $this->frm->getField('linkedin_url')->isURL(Language::getError('InvalidURL'));
            if($this->frm->getField('email')->isFilled()) $this->frm->getField('email')->isEmail(Language::getError('EmailIsInvalid'));

            foreach($this->languages as $key => $language)
            {

            }

            if ($this->frm->isCorrect()) {
                // build the item
                $item['hidden'] = $fields['hidden']->getValue();
                $item['sequence'] = BackendTeamModel::getMaximumSequence() + 1;
                $item['publish_on'] = Model::getUTCDate(null, Model::getUTCTimestamp($this->frm->getField('publish_on_date'), $this->frm->getField('publish_on_time')));
                $item['status'] = $status;
                $item['size'] = $fields['size']->getValue();
                $item['first_name'] = $fields['first_name']->getValue();
                $item['last_name'] = $fields['last_name']->getValue();
                $item['full_name'] = $item['first_name'] .  ' ' . $item['last_name'];
                $item['twitter_site_name'] = $fields['twitter_site_name']->getValue();
                $item['instagram_site_name'] = $fields['instagram_site_name']->getValue();
                $item['pinterest_site_name'] = $fields['pinterest_site_name']->getValue();
                $item['facebook_site_name'] = $fields['facebook_site_name']->getValue();
                $item['email'] = $fields['email']->getValue();
                $item['phone'] = $fields['phone']->getValue();
                $item['linkedin_url'] = $fields['linkedin_url']->getValue();

                $imagePath = SiteHelpersHelper::generateFolders($this->getModule());


                // image provided?
                if ($fields['image']->isFilled()) {
                    // build the image name
                    $item['image'] = uniqid() . '.' . $fields['image']->getExtension();

                    // upload the image & generate thumbnails
                    $fields['image']->generateThumbnails($imagePath, $item['image'], 0777);
                }

                $item['id'] = BackendTeamModel::insert($item);


                if(!empty($this->categories) && Authentication::isAllowedAction('AddCategory'))
                {
                    SiteHelpersModel::insertLinked(
                        $this->frm->getField('categories')->getValue(),
                        'category_id',
                        $item['id'],
                        'team_member_id',
                        'team_linked_catgories'
                    );
                }


                $content = array();


                foreach($this->languages as $language)
                {
                    $specific['team_member_id'] = $item['id'];

                    $specific['language'] = $language['abbreviation'];
                    $specific['function'] = $this->frm->getField('function_'. $language['abbreviation'])->getValue();
                    $specific['description'] = $this->frm->getField('description_'. $language['abbreviation'])->getValue() ? $this->frm->getField('description_'. $language['abbreviation'])->getValue() : null;

                    $specific['seo_url_overwrite'] = $this->frm->getField('seo_url_overwrite_'. $language['abbreviation'])->isChecked() ? 'Y' : 'N';
                    $specific['seo_description_overwrite'] = $this->frm->getField('seo_description_overwrite_'. $language['abbreviation'])->isChecked() ? 'Y' : 'N';
                    $specific['seo_title_overwrite'] = $this->frm->getField('seo_title_overwrite_'. $language['abbreviation'])->isChecked() ? 'Y' : 'N';

                    $specific['url'] =  BackendTeamModel::getURL(CommonUri::getUrl($item['full_name']), $language['abbreviation']);
                    if($specific['seo_url_overwrite'] == 'Y') $specific['url'] = BackendTeamModel::getURL(CommonUri::getUrl($this->frm->getField('url_'. $language['abbreviation'])->getValue()), $language['abbreviation']);

                    $specific['seo_description'] = $item['full_name'];
                    if($specific['seo_description_overwrite'] == 'Y') $specific['seo_description'] = $this->frm->getField('seo_description_'. $language['abbreviation'])->getValue() ? $this->frm->getField('seo_description_'. $language['abbreviation'])->getValue() : null;

                    $specific['seo_title'] = $item['full_name'];
                    if($specific['seo_title_overwrite'] == 'Y') $specific['seo_title'] = $this->frm->getField('seo_title_'. $language['abbreviation'])->getValue() ? $this->frm->getField('seo_title_'. $language['abbreviation'])->getValue() : null;

                    $content[$language['abbreviation']] = $specific;

                     BackendSearchModel::saveIndex(
                        $this->getModule(), $item['id'],
                        array('name' => $item['full_name'], 'description' => $specific['description']),
                        $language['abbreviation']
                    );
                }

                // insert it
               BackendTeamModel::insertContent($content);

                Model::triggerEvent(
                    $this->getModule(), 'after_add', $item
                );
                $this->redirect(
                    Model::createURLForAction('Edit') . '&report=added&id=' . $item['id']
                );
            }
        }
    }
}
