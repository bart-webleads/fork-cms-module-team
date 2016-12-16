<?php

namespace Backend\Modules\Team\Actions;

use Backend\Core\Engine\Base\ActionIndex;
use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\DataGridDB;
use Backend\Core\Language\Language;
use Backend\Core\Engine\Model;
use Backend\Modules\Team\Engine\Model as BackendTeamModel;
use Backend\Core\Engine\Form;
use Backend\Modules\Team\Engine\Category as BackendTeamCategoryModel;

/**
 * This is the index-action (default), it will display the overview of Team posts
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Index extends ActionIndex
{

    private $filter = [];

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->setFilter();
        $this->loadForm();

        $this->loadDataGridTeam();
        $this->loadDataGridTeamDrafts();
        $this->parse();
        $this->display();
    }

    /**
     * Load the dataGrid
     */
    protected function loadDataGridTeam()
    {
        $query = 'SELECT i.id, CONCAT(i.first_name, " ", i.last_name) as name, i.email,  i.sequence, i.hidden
         FROM team AS i
         INNER JOIN team_member_content as c  on i.id = c.team_member_id';

        if(isset($this->filter['categories'] ) && $this->filter['categories'] !== null && count($this->filter['categories']))
        {
            $query .= ' INNER JOIN team_linked_catgories AS cat ON i.id = cat.team_member_id';
        }

        $query .= ' WHERE 1';

        $parameters = array();
        $query .= ' AND c.language = ?';
        $parameters[] = Language::getWorkingLanguage();

        $query .= ' AND i.status = ?';
        $parameters[] = 'active';

        if($this->filter['value']){
            $query .= ' AND (i.first_name LIKE ?';
            $parameters[] = '%' . $this->filter['value'] . '%';

            $query .= ' OR i.last_name LIKE ?';
            $parameters[] = '%' . $this->filter['value'] . '%';

            $query .= ' OR i.email LIKE ?)';
            $parameters[] = '%' . $this->filter['value'] . '%';
        }

        if(isset($this->filter['categories'] ) && $this->filter['categories'] !== null && count($this->filter['categories']))
        {
            $query .= ' AND cat.category_id IN(' . implode(',', array_values($this->filter['categories'])) . ')';
        }

        $query .= 'GROUP BY i.id ORDER BY sequence DESC';

        $this->dataGridTeam = new DataGridDB(
            $query,
            $parameters
        );

        $this->dataGridTeam->enableSequenceByDragAndDrop();
        $this->dataGridTeam->setURL($this->dataGridTeam->getURL() . '&' . http_build_query($this->filter));

        $this->dataGridTeam->setColumnAttributes(
            'name', array('class' => 'title')
        );

        // check if this action is allowed
        if (Authentication::isAllowedAction('Edit')) {
            $this->dataGridTeam->addColumn(
                'edit', null, Language::lbl('Edit'),
                Model::createURLForAction('Edit') . '&amp;id=[id]',
                Language::lbl('Edit')
            );
            $this->dataGridTeam->setColumnURL(
                'name', Model::createURLForAction('Edit') . '&amp;id=[id]'
            );
        }
    }

    /**
     * Load the dataGrid
     */
    protected function loadDataGridTeamDrafts()
    {
        $query = 'SELECT i.id, CONCAT(i.first_name, " ", i.last_name) as name, i.email,  i.sequence, i.hidden
         FROM team AS i
         INNER JOIN team_member_content as c  on i.id = c.team_member_id';

        if(isset($this->filter['categories'] ) && $this->filter['categories'] !== null && count($this->filter['categories']))
        {
            $query .= ' INNER JOIN team_linked_catgories AS cat ON i.id = cat.team_member_id';
        }

        $query .= ' WHERE 1';

        $parameters = array();
        $query .= ' AND c.language = ?';
        $parameters[] = Language::getWorkingLanguage();

        $query .= ' AND i.status = ?';
        $parameters[] = 'draft';



        if($this->filter['value']){
            $query .= ' AND (i.first_name LIKE ?';
            $parameters[] = '%' . $this->filter['value'] . '%';

            $query .= ' OR i.last_name LIKE ?';
            $parameters[] = '%' . $this->filter['value'] . '%';

            $query .= ' OR i.email LIKE ?)';
            $parameters[] = '%' . $this->filter['value'] . '%';

        }

        if(isset($this->filter['categories'] ) && $this->filter['categories'] !== null && count($this->filter['categories']))
        {
            $query .= ' AND cat.category_id IN(' . implode(',', array_values($this->filter['categories'])) . ')';
        }


        $query .= 'GROUP BY i.id ORDER BY sequence DESC ';

        $this->dataGridTeamDrafts = new DataGridDB(
            $query,
            $parameters
        );

        $this->dataGridTeamDrafts->enableSequenceByDragAndDrop();
        $this->dataGridTeamDrafts->setURL($this->dataGridTeamDrafts->getURL() . '&' . http_build_query($this->filter));

        $this->dataGridTeam->setColumnAttributes(
            'name', array('class' => 'title')
        );

        // check if this action is allowed
        if (Authentication::isAllowedAction('Edit')) {
            $this->dataGridTeamDrafts->addColumn(
                'edit', null, Language::lbl('Edit'),
                Model::createURLForAction('Edit') . '&amp;id=[id]',
                Language::lbl('Edit')
            );
            $this->dataGridTeamDrafts->setColumnURL(
                'name', Model::createURLForAction('Edit') . '&amp;id=[id]'
            );
        }
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        $this->frm = new Form('filter', Model::createURLForAction(), 'get');

        $categories = BackendTeamCategoryModel::getForMultiCheckbox();

        $this->frm->addText('value', $this->filter['value']);

        if(!empty($categories) && Authentication::isAllowedAction('AddCategory'))
        {
            $this->frm->addMultiCheckbox(
                'categories',
                $categories,
                '',
                'noFocus'
            );
        }

        // manually parse fields
        $this->frm->parse($this->tpl);
    }


    /**
     * Sets the filter based on the $_GET array.
     */
    private function setFilter()
    {
        $this->filter['categories'] = $this->getParameter('categories', 'array');
        $this->filter['value'] = $this->getParameter('value') == null ? '' : $this->getParameter('value');
    }


    /**
     * Parse the page
     */
    protected function parse()
    {
        // parse the dataGrid if there are results
        $this->tpl->assign('dataGridTeam', (string) $this->dataGridTeam->getContent());
        $this->tpl->assign('dataGridTeamDraft', (string) $this->dataGridTeamDrafts->getContent());
    }
}
