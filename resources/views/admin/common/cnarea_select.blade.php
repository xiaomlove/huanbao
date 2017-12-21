<select name="province" class="form-control">
  <option value="">省</option>
</select>
<select name="city" class="form-control">
  <option value="">市</option>
</select>
<select name="district" class="form-control">
  <option value="">区</option>
</select>
<script>
;(function() {
	var province = "{{ $info->province }}", city = "{{ $info->city }}", district = "{{ $info->district }}";
	var provinceSelect = $("select[name=province]"), citySelect = $("select[name=city]"), districtSelect = $("select[name=district]");
	var provinceUri = "{{ route('cnarea.province') }}", cityUri = "{{ route('cnarea.city') }}", districtUri = "{{ route('cnarea.district') }}"; 

	function setSelect(select, dataSourceUri, firstOptionText, selectedName) {
		var def = $.Deferred();
		$.get(dataSourceUri, function(response) {
			var options = ['<option value="">' + firstOptionText + '</option>'];
			if (response.ret == 0) {
				var pid, selected;
				$.each(response.data, function(index, item) {
					if (item.name == selectedName) {
						pid = item.id;
						selected = " selected";		
					} else {
						selected = "";	
					}
					options.push('<option data-id="' + item.id + '" value="' + item.name + '"' + selected + '>' + item.name + '</option>');
				});
			}
			select.html(options.join(""));
			def.resolve();
		}, "json");
		return def.promise();
	}

	function setProvince(province) {
		return setSelect(provinceSelect, provinceUri, '--省--', province);
	}
	function setCity(city) {
		let index = provinceSelect[0].selectedIndex;
		let pid = provinceSelect[0].options[index].getAttribute("data-id");
		return setSelect(citySelect, cityUri + "?pid=" + pid, '--市--', city);
	}
	function setDistrict(district) {
		let index = citySelect[0].selectedIndex;
		let pid = citySelect[0].options[index].getAttribute("data-id");
		return setSelect(districtSelect, districtUri + "?pid=" + pid, '--区--', district);
	}

	function provinceChange() {
		setCity().then(function() {
			setDistrict();
		})
	}

	function cityChange() {
		setDistrict();
	}
	
	provinceSelect.on("change", provinceChange);
	citySelect.on("change", cityChange);

	//初始化
	setProvince(province).then(function(){
		console.log("set province done!");
		return setCity(city);
	}).then(function() {
		console.log("set city done!");
		return setDistrict(district);
	}).done(function() {
		console.log("set district done!");
		console.log("all done!");
	});
	
})();
</script>
