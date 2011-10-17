<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <table>
    <tbody>
      <?php echo $form; //this line loads the sfGuardFormSignin.class.php and of course its parent class/es ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="<?php echo __('Submit', null, 'sf_guard') ?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>