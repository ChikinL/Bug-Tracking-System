<form id="EditClassForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Classes/editOneClassVerification/<?= $TheClass[0]['class_id'] ?>" method="post">
    <h4><?= $TheClass[0]['year'].'/'.$TheClass[0]['semester'].'/'.$TheClass[0]['section'] ?></h4>
    <table>
        <tr>
            <td><label for="Year">Year*</label></td>
            <td><input type="number" id="Year" name="Year" value="<?= $TheClass[0]['year'] ?>" placeholder="Year" max="2100" min="1900" required></td>
        </tr>
        <tr>
            <td><label>Semester*</label></td>
            <td>
                <select form="EditClassForm" name="Semester" required>
                    <option <? if ($TheClass[0]['semester'] == 'Summer') { ?> selected <? } ?>>Summer</option>
                    <option <? if ($TheClass[0]['semester'] == 'Fall') { ?> selected <? } ?>>Fall</option>
                    <option <? if ($TheClass[0]['semester'] == 'Winter') { ?> selected <? } ?>>Winter</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Section*</label></td>
            <td>
                <select form="EditClassForm" name="Section" required>
                    <option <? if ($TheClass[0]['section'] == 'A') { ?> selected <? } ?>>A</option>
                    <option <? if ($TheClass[0]['section'] == 'B') { ?> selected <? } ?>>B</option>
                    <option <? if ($TheClass[0]['section'] == 'C') { ?> selected <? } ?>>C</option>
                    <option <? if ($TheClass[0]['section'] == 'D') { ?> selected <? } ?>>D</option>
                    <option <? if ($TheClass[0]['section'] == 'E') { ?> selected <? } ?>>E</option>
                    <option <? if ($TheClass[0]['section'] == 'F') { ?> selected <? } ?>>F</option>
                    <option <? if ($TheClass[0]['section'] == 'G') { ?> selected <? } ?>>G</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Update" >
                <input type="button" value="Back" onclick="window.location='<?= base_url(); ?>index.php?/Classes/showAllClassesView'" >
            </td>
            <td>
                <pre><?= $result ?></pre>
            </td>
        </tr>
    </table>

</form> 