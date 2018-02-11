;(function() {
    const CnSelect = function (containerId) {
        var container = $('#' + containerId);

        var provinceSelect = container.find("select[name=province]:first"), citySelect = container.find("select[name=city]:first"), districtSelect = container.find("select[name=district]:first");
        var province = provinceSelect.attr("data-value"), city = citySelect.attr("data-value"), district = districtSelect.attr("data-value");
        var provinceUri = provinceSelect.attr("data-uri"), cityUri = citySelect.attr("data-uri"), districtUri = districtSelect.attr("data-uri");

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
        this.init = () => {
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
        }
    }

    CnSelect.init = (containerId) => {
        return (new CnSelect(containerId)).init();
    }

    window.CnSelect = CnSelect;
})();