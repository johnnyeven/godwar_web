$(function() {
	var id;
	var pre_job;
	var dup;
	$("#tmp").find("div.job_item").each(function() {
		id = $(this).find("span.id").text();
		pre_job = $(this).find("span.pre_job").text();
		dup = $(this).clone();
		dup.attr("id", "job_" + id);

		if($("#job_" + pre_job).length > 0) {
			$("#job_" + pre_job + " > div.sub_job").append(dup);
			if($("#job_" + pre_job).hasClass("job_root")) {
				dup.addClass("job_1");
			} else if($("#job_" + pre_job).hasClass("job_1")) {
				dup.addClass("job_2");
			} else if($("#job_" + pre_job).hasClass("job_2")) {
				dup.addClass("job_3");
			}
		}
		else
		{
			dup.addClass("job_root");
			$("#job").append(dup);
		}
	});
	$("#tmp").remove();

	$("#job div.job_item").click(function(event) {
		var id = $(this).attr("id");
		$("#job_detail > div.detail").hide();
		$("#" + id + "_skill").show();

		event.stopPropagation();
	});
});