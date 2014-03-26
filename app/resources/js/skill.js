$(function() {
	var id;
	var pre_job;
	var dup;
	$("#tmp").find("div.job_item").each(function() {
		id = $(this).find("span.id").text();
		pre_job = $(this).find("span.pre_job").text();
		dup = $(this).clone();
		dup.attr("id", "job_" + id);
		$("#job").append(dup);

		if($("#job_" + pre_job).length > 0) {
			$("#job_" + pre_job).find("div.sub_job").append(dup);
			$(this).remove();
		}
	});
	$("#tmp").remove();
});