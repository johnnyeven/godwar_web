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
			console.log("#job_" + pre_job + "添加");
			console.log($("#job_" + pre_job + " > div.sub_job").length);
		}
		else
		{
			$("#job").append(dup);
			console.log("根添加");
		}
	});
	$("#tmp").remove();
});