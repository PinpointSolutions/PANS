<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Projects', 'doctrine');

/**
 * BaseProjects
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $title
 * @property string $organisation
 * @property string $description
 * @property boolean $has_additional_info
 * @property string $major_ids
 * @property string $skill_set_ids
 * @property string $nomination_round
 * @property integer $proj_num
 * @property Doctrine_Collection $ProjectAllocations
 * @property Doctrine_Collection $Project2
 * 
 * @method integer             getId()                  Returns the current record's "id" value
 * @method string              getTitle()               Returns the current record's "title" value
 * @method string              getOrganisation()        Returns the current record's "organisation" value
 * @method string              getDescription()         Returns the current record's "description" value
 * @method boolean             getHasAdditionalInfo()   Returns the current record's "has_additional_info" value
 * @method string              getMajorIds()            Returns the current record's "major_ids" value
 * @method string              getSkillSetIds()         Returns the current record's "skill_set_ids" value
 * @method string              getNominationRound()     Returns the current record's "nomination_round" value
 * @method integer             getProjNum()             Returns the current record's "proj_num" value
 * @method Doctrine_Collection getProjectAllocations()  Returns the current record's "ProjectAllocations" collection
 * @method Doctrine_Collection getProject2()            Returns the current record's "Project2" collection
 * @method Projects            setId()                  Sets the current record's "id" value
 * @method Projects            setTitle()               Sets the current record's "title" value
 * @method Projects            setOrganisation()        Sets the current record's "organisation" value
 * @method Projects            setDescription()         Sets the current record's "description" value
 * @method Projects            setHasAdditionalInfo()   Sets the current record's "has_additional_info" value
 * @method Projects            setMajorIds()            Sets the current record's "major_ids" value
 * @method Projects            setSkillSetIds()         Sets the current record's "skill_set_ids" value
 * @method Projects            setNominationRound()     Sets the current record's "nomination_round" value
 * @method Projects            setProjNum()             Sets the current record's "proj_num" value
 * @method Projects            setProjectAllocations()  Sets the current record's "ProjectAllocations" collection
 * @method Projects            setProject2()            Sets the current record's "Project2" collection
 * 
 * @package    PANS
 * @subpackage model
 * @author     Daniel Brose
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProjects extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('projects');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('title', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('organisation', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('description', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('has_additional_info', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => false,
             ));
        $this->hasColumn('major_ids', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('skill_set_ids', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('nomination_round', 'string', 64, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 64,
             ));
        $this->hasColumn('proj_num', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('ProjectAllocations', array(
             'local' => 'id',
             'foreign' => 'project_id'));

        $this->hasMany('StudentPrefs as Project2', array(
             'local' => 'id',
             'foreign' => 'proj_pref_2'));
    }
}