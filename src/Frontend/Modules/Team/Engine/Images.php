<?php

namespace Frontend\Modules\Team\Engine;

use Frontend\Core\Engine\Model as FrontendModel;


/**
 * In this file we store all generic functions that we will be using in the Team module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
class Images
{
    public static function getAll($id)
    {
       $db = FrontendModel::get('database');

       $return =  (array) $db->getRecords(
           'SELECT i.*, c.name
            FROM team_member_images AS i
            INNER JOIN team_member_images_content AS c on c.image_id = i.id
            WHERE i.team_member_id = ? GROUP BY i.id ORDER BY i.sequence',
           array((int) $id)
       );

       return  $return;
    }
}
