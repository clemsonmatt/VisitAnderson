<script>
$(document).ready(function() {
    $('.btn-add').on('click', function(e) {
        var parent = $(this).parent().parent();
        
        var operator = $(parent).find('.query-operator').val();
        var field_name = $(parent).find('.query-field').attr('name');
        var field_value = $(parent).find('.query-field').val();

        var query = field_name + ' ' +  operator + ' \'' + field_value + '\'';
        if (field_value !== '') {
            $('.query-items').append('<option value=\'' + field_name + '\'>' + query + '</option>');
            console.log(field_name + " " + operator + " " + field_value);
        }
    });
    
    $('.btn-delete').on('click', function(e) {
        $('.query-items option:selected').remove();
    });

    $('.btn-or').on('click', function(e) {
        $('.query-items').append('<option>OR</option>');
    });

    var ToStateName = function(s) {
        console.log("ToStateName: " + s);
        switch (s) {
        case 'AL': return 'Alabama';
        case 'AK': return 'Alaska';
        case 'AZ': return 'Arizona';
        case 'AR': return 'Arkansas';
        case 'CA': return 'California';
        case 'CO': return 'Colorado';
        case 'CT': return 'Connecticut';
        case 'DE': return 'Delaware';
        case 'DC': return 'District of Columbia';
        case 'FL': return 'Florida';
        case 'GA': return 'Georgia';
        case 'HI': return 'Hawaii';
        case 'ID': return 'Idaho';
        case 'IL': return 'Illinois';
        case 'IN': return 'Indiana';
        case 'IA': return 'Iowa';
        case 'KS': return 'Kansas';
        case 'KY': return 'Kentucky';
        case 'LA': return 'Louisiana';
        case 'ME': return 'Maine';
        case 'MD': return 'Maryland';
        case 'MA': return 'Massachusetts';
        case 'MI': return 'Michigan';
        case 'MN': return 'Minnesota';
        case 'MS': return 'Mississippi';
        case 'MO': return 'Missouri';
        case 'MT': return 'Montana';
        case 'NE': return 'Nebraska';
        case 'NV': return 'Nevada';
        case 'NH': return 'New Hampshire';
        case 'NJ': return 'New Jersey';
        case 'NM': return 'New Mexico';
        case 'NY': return 'New York';
        case 'NC': return 'North Carolina';
        case 'ND': return 'North Dakota';
        case 'OH': return 'Ohio';
        case 'OK': return 'Oklahoma';
        case 'OR': return 'Oregon';
        case 'PA': return 'Pennsylvania';
        case 'RI': return 'Rhode Island';
        case 'SC': return 'South Carolina';
        case 'TN': return 'Tennessee';
        case 'TX': return 'Texas';
        case 'UT': return 'Utah';
        case 'VT': return 'Vermont';
        case 'VA': return 'Virginia';
        case 'WA': return 'Washington';
        case 'WV': return 'West Virginia';
        case 'WI': return 'Wisconsin';
        case 'WY': return 'Wyoming';
        default: return '';
        };
    }

    $('.btn-execute').on('click', function(e) {
        var selectors = [];
        var wheres = [];
        var grouping = [];
        $('.query-items option').each(function(index, value) {
            var field = $(value).val();
            console.log('field: ' + $(value).text());
            wheres.push($(value).text());
        });

        $('.checkbox-selector:checked').each(function(index, value) {
            selectors.push($(value).val());
        });

        $('#groupby option:selected').each(function(index, value) {
            grouping.push($(value).val());
        });

        var aggregate = $('.query-aggregate').val();
        if (aggregate !== '')
            selectors.push(aggregate);

        console.log(wheres);
        //var query = 'SELECT ' + selectors.join(', ') + ' FROM visitor INNER JOIN visiting ON visiting.person_id = person.id ';
        var query = 'SELECT ' + selectors.join(', ') + ' FROM mapping INNER JOIN visitor ON mapping.person_id = visitor.id INNER JOIN visiting ON mapping.table_id = visiting.id ';
        if (wheres.length > 0) 
            query += 'WHERE ' + wheres.join(' AND ');
        if (grouping.length > 0)
            query += ' GROUP BY ' + grouping.join(', ') ;
        query += ';';

        query = query.replace(/AND OR AND/g, 'OR');
        console.log(query);

        $.post('includes/query-execute.php', { sql: query }, function(data) {
            console.log(data);
            data = $.parseJSON(data);

            $('.table').html("");

            var rows = '';

            rows += '<thead>';
            for (var i in selectors) {
                var label = '';
                switch (selectors[i]) {
                case 'visitor.state':
                    label = 'State';
                    break;
                case 'visitor.city':
                    label = 'City';
                    break;
                case 'visitor.zip':
                    label = 'Zip';
                    break;
                case 'visiting.reason':
                    label = 'Visit Reason';
                    break;
                case 'visiting.interests':
                    label = 'Interests';
                    break;
                case 'visiting.visit_date':
                    label = 'Planned Visit';
                    break;
                default:
                    label = selectors[i];
                };
                if (selectors[i].indexOf('count') >= 0)
                    label = 'Count';

                rows += '<th>' + label + '</th>';
            }
            rows += '</thead>';

            for (var i in data) {
                rows += '<tr>';

                var re = /_/g;
                for (var j in data[i]) {
                    var ret = ToStateName(data[i][j]);
                    if (ret === '')
                        rows += '<td>' + data[i][j] + '</td>';
                    else
                        rows += '<td>' + ret.replace(/_/g, ' ') + '</td>';
                }
                rows += '</tr>';
            }

            $('.table').append(rows);
        });
    });
});
</script>

   <div class="row">
      <div class="well">
         <div class="row">
            <div class="col-lg-4">
               <form>
                  <label class="form-label">State</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox" class="checkbox-selector" value="visitor.state"></input>
                     </div>
                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value="<>"><></option>
                        </select>
                     </div>
                     <div class="col-lg-6">
                        <select class="selectpicker form-control query-field" name="visitor.state">
                           <option value="">Select</option>
                           <option value="AL">Alabama</option>
                           <option value="AK">Alaska</option>
                           <option value="AZ">Arizona</option>
                           <option value="AR">Arkansas</option>
                           <option value="CA">California</option>
                           <option value="CO">Colorado</option>
                           <option value="CT">Connecticut</option>
                           <option value="DE">Delaware</option>
                           <option value="DC">District Of Columbia</option>
                           <option value="FL">Florida</option>
                           <option value="GA">Georgia</option>
                           <option value="HI">Hawaii</option>
                           <option value="ID">Idaho</option>
                           <option value="IL">Illinois</option>
                           <option value="IN">Indiana</option>
                           <option value="IA">Iowa</option>
                           <option value="KS">Kansas</option>
                           <option value="KY">Kentucky</option>
                           <option value="LA">Louisiana</option>
                           <option value="ME">Maine</option>
                           <option value="MD">Maryland</option>
                           <option value="MA">Massachusetts</option>
                           <option value="MI">Michigan</option>
                           <option value="MN">Minnesota</option>
                           <option value="MS">Mississippi</option>
                           <option value="MO">Missouri</option>
                           <option value="MT">Montana</option>
                           <option value="NE">Nebraska</option>
                           <option value="NV">Nevada</option>
                           <option value="NH">New Hampshire</option>
                           <option value="NJ">New Jersey</option>
                           <option value="NM">New Mexico</option>
                           <option value="NY">New York</option>
                           <option value="NC">North Carolina</option>
                           <option value="ND">North Dakota</option>
                           <option value="OH">Ohio</option>
                           <option value="OK">Oklahoma</option>
                           <option value="OR">Oregon</option>
                           <option value="PA">Pennsylvania</option>
                           <option value="RI">Rhode Island</option>
                           <option value="SC">South Carolina</option>
                           <option value="SD">South Dakota</option>
                           <option value="TN">Tennessee</option>
                           <option value="TX">Texas</option>
                           <option value="UT">Utah</option>
                           <option value="VT">Vermont</option>
                           <option value="VA">Virginia</option>
                           <option value="WA">Washington</option>
                           <option value="WV">West Virginia</option>
                           <option value="WI">Wisconsin</option>
                           <option value="WY">Wyoming</option>
                        </select>
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-sm btn-green btn-add"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>

                  <label class="form-label">City</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox"></input>
                     </div>

                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value="<>"><></option>
                           <option value="LIKE">LIKE</option>
                        </select>
                     </div>

                     <div class="col-lg-6">
                        <input type="text" class="form-control query-field" name="visitor.city">
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-sm btn-green btn-add"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>

                  <label class="form-label">Zip</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox"></input>
                     </div>

                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value="<>"><></option>
                           <option value=">">></option>
                           <option value=">=">>=</option>
                           <option value="<"><</option>
                           <option value="<="><=</option>
                           <option value="LIKE">LIKE</option>
                        </select>
                     </div>

                     <div class="col-lg-6">
                        <input type="text" class="form-control query-field" name="visitor.zip">
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-sm btn-green btn-add"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>
                  
                  <label class="form-label">Reason</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox" class="checkbox-selector" value="visiting.reason"></input>
                     </div>
                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value="<>"><></option>
                        </select>
                     </div>

                     <div class="col-lg-6">
                        <select class="selectpicker form-control query-field" name="visiting.reason">
                           <option value="">Select</option>
                           <option value="business">Business</option>
                           <option value="vacation">Vacation</option>
                           <option value="area_resident">Area Resident</option>
                           <option value="convention_atendee">Convention Atendee</option>
                           <option value="looking_at_/_attending_area_school">Looking at / Attending area school</option>
                           <option value="relocation">Relocation</option>
                           <option value="weekend_getaway">Weekend Getaway</option>
                        </select>
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-green btn-sm btn-add"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>

                  <label class="form-label">Interests</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox" class="checkbox-selector" value="visiting.interests"></input>
                     </div>
                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value="<>"><></option>
                        </select>
                     </div>

                     <div class="col-lg-6">
                        <select class="selectpicker form-control query-field" name="visiting.interests">
                           <option vlaue="">Select</option>
                           <option vlaue="accomodations">Accomodations</option>
                           <option value="arts_&_entertainment">Arts & Entertainment</option>
                           <option value="attractions">Attractions</option>
                           <option vlaue="dining">Dining</option>
                           <option vlaue="family_fun">Family Fun</option>
                           <option vlaue="fishing">Fishing</option>
                           <option vlaue="golf">Golf</option>
                           <option vlaue="outdoor_adventure">Outdoor Adventure</option>
                           <option vlaue="meeting_facilities">Meeting Facilities</option>
                           <option vlaue="museums">Museums</option>
                           <option vlaue="relocation">Relocation</option>
                           <option vlaue="shopping">Shopping</option>
                           <option vlaue="sporting_events">Sporting Events</option>
                           <option vlaue="weddings">Weddings</option>
                        </select>
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-green btn-sm btn-add" type="button"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>

                  <label class="form-label">Planned Visit</label>
                  <div class="row">
                     <div class="col-lg-1">
                        <input type="checkbox" class="checkbox-selector" value="visiting.visit_date"></input>
                     </div>
                     <div class="col-lg-3">
                        <select class="selectpicker form-control query-operator">
                           <option value="=">=</option>
                           <option value=">">></option>
                           <option value=">=">>=</option>
                           <option value="<"><</option>
                           <option value="<="><=</option>
                           <option value="<>"><></option>
                        </select>
                     </div>

                     <div class="col-lg-6">
                        <select class="selectpicker form-control query-field" name="visiting.visit_date">
                           <option value="">Select</option>
                           <option value="january">January</option>      
                           <option value="january">February</option>
                           <option value="february">March</option>
                           <option value="april">April</option>
                           <option value="may">May</option>
                           <option value="june">June</option>
                           <option value="july">July</option>
                           <option value="august">August</option>
                           <option value="september">September</option>
                           <option value="october">October</option>
                           <option value="november">November</option>
                           <option value="december">December</option>
                        </select>
                     </div>
                     <div class="col-lg-1">
                        <button type="button" class="btn btn-green btn-sm btn-add"><i class="fa fa-plus"></i></button>
                     </div>
                  </div>
                  
            </div>
            <br />
            <div class="col-lg-3">
               <select size="15" class="form-control query-items" style="width: 100%"></select>
               <br />
               <div class="row">
                  <div class="col-lg-1">
                     <button type="button" class="btn btn-danger btn-sm btn-delete">Remove</button>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-1">
                     <button type="button" class="btn btn-info btn-sm btn-or">OR</button>
                  </div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-1">
                     <button type="button" class="btn btn-green btn-sm btn-execute">Execute Query</button>
                  </div>
               </div>
            </div>
            <div class="col-lg-3">
                  <label class="form-label">Aggregate</label>
                  <div class="row">
                     <div class="col-lg-10">
                        <select class="selectpicker form-control query-aggregate">
                           <option value="">Select</option>
                           <option value="count(visitor.state)">State</option>
                           <option value="count(visitor.city)">City</option>
                           <option value="count(visitor.zip)">Zip</option>
                           <option value="count(visiting.reason)">Reason</option>
                           <option value="count(visiting.interests)">Interests</option>
                        </select>
                     </div>
                  </div>
                  <label class="form-label">Group by</label>
                  <div class="row">
                     <div class="col-lg-10">
                        <select class="selectpicker form-control query-groupping" id="groupby" multiple="mutliple">
                           <option value="visitor.state">State</option>
                           <option value="visitor.city">City</option>
                           <option value="visitor.zip">Zip</option>
                           <option value="visiting.reason">Reason</option>
                           <option value="visiting.interests">Interests</option>
                        </select>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="col-lg-12">
         <table class="table table-striped table-condensed table-hover table-bordered">
         </table>
      </div>
   </div>
