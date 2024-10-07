jQuery(document).ready(function ($) {
  // Function to update row numbers after deletion
  function updateRowNumbers() {
    $("#dynamic-rows-table tbody tr").each(function (index) {
      $(this)
        .find("input")
        .each(function () {
          let oldName = $(this).attr("name");
          let newName = oldName.replace(/\[\d+\]/, `[${index}]`);
          $(this).attr("name", newName);

          let oldId = $(this).attr("id");
          let newId = oldId.replace(/_\d+$/, `_${index}`);
          $(this).attr("id", newId);
        });
    });
  }

  $("#add-row").on("click", function () {
    let rowCount = $("#dynamic-rows-table tbody tr").length;
    let newRow = `
        <tr valign="top">
            <td><input type="text" id="repeater_name_${rowCount}" name="acf_dynamic_option_plugin_options[${rowCount}][repeater_name]" value="" /></td>
            <td><input type="text" id="option_page_name_${rowCount}" name="acf_dynamic_option_plugin_options[${rowCount}][option_page_name]" value="" /></td>
            <td><input type="text" id="key_field_name_${rowCount}" name="acf_dynamic_option_plugin_options[${rowCount}][key_field_name]" value="" /></td>
            <td><input type="text" id="name_field_name_${rowCount}" name="acf_dynamic_option_plugin_options[${rowCount}][name_field_name]" value="" /></td>
            <td><input type="text" id="class_name_${rowCount}" name="acf_dynamic_option_plugin_options[${rowCount}][class_name]" value="" /></td>
            <td><button type="button" class="delete-row button">Delete Row</button></td>
        </tr>
    `;
    $("#dynamic-rows-table tbody").append(newRow);
  });

  // Delete Row functionality
  $("#dynamic-acf-options-form").on("click", ".delete-row", function () {
    if (confirm("Are you sure you want to delete this row?")) {
      $(this).closest("tr").remove();
      updateRowNumbers(); // Re-index the rows
    }
  });
});
