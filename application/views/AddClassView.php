<form id="AddClassForm" style=" display: inline-block;" action="<?= base_url(); ?>index.php?/Classes/addOneClassVerification" method="post">
    <table>
        <tr>
            <td><label for="Year">Year*</label></td>
            <td><input type="number" id="Year" name="Year" placeholder="Year" max="2100" min="1900" required="required" value="<? date_default_timezone_set('America/Toronto'); echo date('Y'); ?>"></td>
        </tr>
        <tr>
            <td><label>Semester*</label></td>
            <td>
                <select form="AddClassForm" name="Semester" required>
                    <option>Fall</option>
                    <option>Winter</option>
                    <option>Summer</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Section*</label></td>
            <td>
                <select form="AddClassForm" name="Section" required>
                    <option>A</option>
                    <option>B</option>
                    <option>C</option>
                    <option>D</option>
                    <option>E</option>
                    <option>F</option>
                    <option>G</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Project</label></td> 
            <td>
                <? if (sizeof($AllProjects)!= 0) { ?>
                <select form="AddClassForm" name="Projects[]" multiple>
                    <? foreach ($AllProjects as $Project){?>
                    <option value="<?= $Project['project_id'] ?>"><?= $Project['project_name'] ?></option>
                    <? } ?>
                </select>
                <? } else { ?>
                No Project In The System Yet
                <? } ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Add" >
                <input type="reset" value="Clean" >
            </td>
            <td>
                <pre><?= $result ?></pre>
            </td>
        </tr>
    </table>

</form> 