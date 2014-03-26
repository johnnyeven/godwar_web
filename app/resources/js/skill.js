$(function() {
	var pre_job;
	$("#job").find("div.job_item").each(function() {
		pre_job = $(this).find("span.pre_job").text();

		if($("#job_" + pre_job).length > 0) {
			$("#job_" + pre_job).find("div.sub_job").append($(this));
			//$(this).remove();
		}
	});
});