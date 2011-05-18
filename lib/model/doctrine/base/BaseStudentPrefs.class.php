<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('StudentPrefs', 'doctrine');

/**
 * BaseStudentPrefs
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $snum
 * @property integer $year
 * @property integer $pass_fail_pm
 * @property string $major_ids
 * @property float $gpa
 * @property integer $proj_pref_1
 * @property integer $proj_pref_2
 * @property integer $proj_pref_3
 * @property integer $proj_pref_4
 * @property integer $proj_pref_5
 * @property string $skill_set_ids
 * @property integer $y_stu_pref_1
 * @property integer $y_stu_pref_2
 * @property integer $y_stu_pref_3
 * @property integer $y_stu_pref_4
 * @property integer $y_stu_pref_5
 * @property integer $n_stu_pref_1
 * @property integer $n_stu_pref_2
 * @property integer $n_stu_pref_3
 * @property integer $n_stu_pref_4
 * @property integer $n_stu_pref_5
 * @property string $proj_just_1
 * @property string $proj_just_2
 * @property string $proj_just_3
 * @property string $proj_just_4
 * @property string $proj_just_5
 * @property StudentUsers $StudentUsers
 * 
 * @method integer      getSnum()          Returns the current record's "snum" value
 * @method integer      getYear()          Returns the current record's "year" value
 * @method integer      getPassFailPm()    Returns the current record's "pass_fail_pm" value
 * @method string       getMajorIds()      Returns the current record's "major_ids" value
 * @method float        getGpa()           Returns the current record's "gpa" value
 * @method integer      getProjPref1()     Returns the current record's "proj_pref_1" value
 * @method integer      getProjPref2()     Returns the current record's "proj_pref_2" value
 * @method integer      getProjPref3()     Returns the current record's "proj_pref_3" value
 * @method integer      getProjPref4()     Returns the current record's "proj_pref_4" value
 * @method integer      getProjPref5()     Returns the current record's "proj_pref_5" value
 * @method string       getSkillSetIds()   Returns the current record's "skill_set_ids" value
 * @method integer      getYStuPref1()     Returns the current record's "y_stu_pref_1" value
 * @method integer      getYStuPref2()     Returns the current record's "y_stu_pref_2" value
 * @method integer      getYStuPref3()     Returns the current record's "y_stu_pref_3" value
 * @method integer      getYStuPref4()     Returns the current record's "y_stu_pref_4" value
 * @method integer      getYStuPref5()     Returns the current record's "y_stu_pref_5" value
 * @method integer      getNStuPref1()     Returns the current record's "n_stu_pref_1" value
 * @method integer      getNStuPref2()     Returns the current record's "n_stu_pref_2" value
 * @method integer      getNStuPref3()     Returns the current record's "n_stu_pref_3" value
 * @method integer      getNStuPref4()     Returns the current record's "n_stu_pref_4" value
 * @method integer      getNStuPref5()     Returns the current record's "n_stu_pref_5" value
 * @method string       getProjJust1()     Returns the current record's "proj_just_1" value
 * @method string       getProjJust2()     Returns the current record's "proj_just_2" value
 * @method string       getProjJust3()     Returns the current record's "proj_just_3" value
 * @method string       getProjJust4()     Returns the current record's "proj_just_4" value
 * @method string       getProjJust5()     Returns the current record's "proj_just_5" value
 * @method StudentUsers getStudentUsers()  Returns the current record's "StudentUsers" value
 * @method StudentPrefs setSnum()          Sets the current record's "snum" value
 * @method StudentPrefs setYear()          Sets the current record's "year" value
 * @method StudentPrefs setPassFailPm()    Sets the current record's "pass_fail_pm" value
 * @method StudentPrefs setMajorIds()      Sets the current record's "major_ids" value
 * @method StudentPrefs setGpa()           Sets the current record's "gpa" value
 * @method StudentPrefs setProjPref1()     Sets the current record's "proj_pref_1" value
 * @method StudentPrefs setProjPref2()     Sets the current record's "proj_pref_2" value
 * @method StudentPrefs setProjPref3()     Sets the current record's "proj_pref_3" value
 * @method StudentPrefs setProjPref4()     Sets the current record's "proj_pref_4" value
 * @method StudentPrefs setProjPref5()     Sets the current record's "proj_pref_5" value
 * @method StudentPrefs setSkillSetIds()   Sets the current record's "skill_set_ids" value
 * @method StudentPrefs setYStuPref1()     Sets the current record's "y_stu_pref_1" value
 * @method StudentPrefs setYStuPref2()     Sets the current record's "y_stu_pref_2" value
 * @method StudentPrefs setYStuPref3()     Sets the current record's "y_stu_pref_3" value
 * @method StudentPrefs setYStuPref4()     Sets the current record's "y_stu_pref_4" value
 * @method StudentPrefs setYStuPref5()     Sets the current record's "y_stu_pref_5" value
 * @method StudentPrefs setNStuPref1()     Sets the current record's "n_stu_pref_1" value
 * @method StudentPrefs setNStuPref2()     Sets the current record's "n_stu_pref_2" value
 * @method StudentPrefs setNStuPref3()     Sets the current record's "n_stu_pref_3" value
 * @method StudentPrefs setNStuPref4()     Sets the current record's "n_stu_pref_4" value
 * @method StudentPrefs setNStuPref5()     Sets the current record's "n_stu_pref_5" value
 * @method StudentPrefs setProjJust1()     Sets the current record's "proj_just_1" value
 * @method StudentPrefs setProjJust2()     Sets the current record's "proj_just_2" value
 * @method StudentPrefs setProjJust3()     Sets the current record's "proj_just_3" value
 * @method StudentPrefs setProjJust4()     Sets the current record's "proj_just_4" value
 * @method StudentPrefs setProjJust5()     Sets the current record's "proj_just_5" value
 * @method StudentPrefs setStudentUsers()  Sets the current record's "StudentUsers" value
 * 
 * @package    PANS
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStudentPrefs extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('student_prefs');
        $this->hasColumn('snum', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('year', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('pass_fail_pm', 'integer', 1, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 1,
             ));
        $this->hasColumn('major_ids', 'string', 32, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 32,
             ));
        $this->hasColumn('gpa', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('proj_pref_1', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('proj_pref_2', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('proj_pref_3', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('proj_pref_4', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('proj_pref_5', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('skill_set_ids', 'string', 32, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 32,
             ));
        $this->hasColumn('y_stu_pref_1', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('y_stu_pref_2', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('y_stu_pref_3', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('y_stu_pref_4', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('y_stu_pref_5', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('n_stu_pref_1', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('n_stu_pref_2', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('n_stu_pref_3', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('n_stu_pref_4', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('n_stu_pref_5', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('proj_just_1', 'string', 2048, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2048,
             ));
        $this->hasColumn('proj_just_2', 'string', 2048, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2048,
             ));
        $this->hasColumn('proj_just_3', 'string', 2048, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2048,
             ));
        $this->hasColumn('proj_just_4', 'string', 2048, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2048,
             ));
        $this->hasColumn('proj_just_5', 'string', 2048, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2048,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('StudentUsers', array(
             'local' => 'snum',
             'foreign' => 'snum'));
    }
}