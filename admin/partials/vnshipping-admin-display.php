<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://plugin.com/
 * @since      1.0.0
 *
 * @package    Vnshipping
 * @subpackage Vnshipping/admin/partials
 */
?>

    <!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <div>
        <h1>Импорт заказов</h1>
        <p>Выберите дату и время для импорта заказов</p>
        <form method="post">
            <div>
                <p>
                    <label for="datetime_start">С</label>
                    <input id="datetime_start" type="date" name="date_start" value="<?=date("Y-m-d")?>">
                    <input type="time" name="time_start" value="00:00">
                </p>
                <p>
                    <label for="datetime_end">До</label>
                    <input id="datetime_end" type="date"  name="date_end" value="<?=date("Y-m-d")?>">
                    <input type="time"  name="time_end" value="23:59">
                </p>

                <input type="hidden" name="action" value="shipping">
                <input type="submit" name="submit" class="button action">
            </div>
        </form>
    </div>
<?php

?>