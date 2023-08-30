/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React, { Component } from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image,
  AsyncStorage,
  ActivityIndicator

} from 'react-native';
import moment from 'moment';
import { NavigationEvents, NavigationActions } from 'react-navigation';
import { Segment, Icon, Container, Header, Content, Footer, FooterTab, Button, Text, Item, Input, Form, Picker, DatePicker } from 'native-base';
import InputSpinner from "react-native-input-spinner";
import Toast, { DURATION } from 'react-native-easy-toast'
import PickerModal from 'react-native-picker-modal-view';
import SegmentedControlTab from "react-native-segmented-control-tab";

const list22 = [
  { Id: 1, Name: 'CHL', Value: 'CHL' },
  { Id: 2, Name: 'Test2 Name', Value: 'Test2 Value' },
  { Id: 3, Name: 'Test3 Name', Value: 'Test3 Value' },
  { Id: 4, Name: 'Test4 Name', Value: 'Test4 Value' }
]

const stewardcunsultlist = [
  { Id: 1, Name: 'Yes', Value: 'Yes' },
  { Id: 2, Name: 'No', Value: 'No' }

]
const CustomHeader = ({ navigation }) => (

  <View style={{ flexDirection: 'row', justifyContent: 'center', backgroundColor: 'blue' }}>
    <TouchableOpacity onPress={() => navigation.goBack(null)} style={{ flex: 1, justifyContent: "flex-start" }} transparent>
      <Image
        source={require('./image/BackLight.png')}
      />

    </TouchableOpacity>

    <View style={{ justifyContent: 'center', paddingRight: 15 }}>
      {/* <Text>Conference</Text> */}
    </View>

  </View>

);

export default class Newentry extends Component {

  constructor(props) {
    super(props);
    this.state = {
      pat_name: '',
      pat_address: '',
      selectedIndex: 0,
      selectedItem: {},
      activePage: 1,
      number: 0,
      carelistdata: [],
      Dxlistdata: [],
      Rxlistdata: [],
      Doclistdata: [],
      animating: false,
      iniidotnumber: 0,
      selectedIndexdata: 'Hospital',
      MDSlistdata: [],
      total_day_patient_stay: '',
      patient_unique_id: '',
      date_of_start_abx: '',
      selectedcare:'',
      selecteddoctor:'',
      selectedsteward:'',
      selectedstewardconsult:'',
      // selectedcare:'Care Unit Name ',
      // Initialdx:'Initial Dx',
      // selectedRx:'Initial Rx',
      // selecteddoctor:'Doctor',
      // selectedsteward:'MD Steward',
      // selectedstewardconsult:'MD Steward Consult',

      selectedcarename: 'Search Care Unit',
      Initialdxname: 'Initial Dx',
      selectedRxname: 'Initial Rx',
      selecteddoctorname: 'Provider MD',
      selectedstewardname: 'MD Steward',
      selectedstewardconsultname: 'MD Steward Consult',
      selectedIndexp_type: 0,
      patientlistarray: [],
      patient_unique_placeholder: 'Select Patient ID',
      pct:''
    };
  }

  componentWillMount() {
    this.setState({
      animating: true
    })
    AsyncStorage.getItem('userdata', (err, result) => {
      const item = JSON.parse(result);
      const key = item.login_session_key;

      let formdata = new FormData();
      formdata.append("login_session_key", key)

      fetch('http://idcaresteward.com/api/v1/user/careUnit', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          this.items = responseJson.response.map((item, key) =>
            this.setState({
              carelistdata: [...this.state.carelistdata, { 'Id': item.id, 'Name': item.name, 'Value': item.id }]
            })

          );

        })
        .catch(err => {
          //  console.log(err)
        })

      //initial Dx
      fetch('http://idcaresteward.com/api/v1/user/initialDx', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          this.items = responseJson.response.map((item, key) =>
            this.setState({
              Dxlistdata: [...this.state.Dxlistdata, { 'Id': item.id, 'Name': item.name, 'Value': item.id }]
            }),
            this.setState({
              animating: false
            })
          );

        })
        .catch(err => {
          //  console.log(err)
        })
      //end//

      //initial Rx
      fetch('http://idcaresteward.com/api/v1/user/initialRx', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          this.items = responseJson.response.map((item, key) =>
            this.setState({
              Rxlistdata: [...this.state.Rxlistdata, { 'Id': item.id, 'Name': item.name, 'Value': item.id }]
            })

          );

        })
        .catch(err => {
          //  console.log(err)
        })
      //end//

      //initial Doctor
      fetch('http://idcaresteward.com/api/v1/user/doctors', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          this.items = responseJson.response.map((item, key) =>
            this.setState({
              Doclistdata: [...this.state.Doclistdata, { 'Id': item.id, 'Name': item.name, 'Value': item.id }]
            })

          );

        })
        .catch(err => {
          //  console.log(err)
        })
      //end//

      fetch('http://idcaresteward.com/api/v1/user/md_steward', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {
          console.log(responseJson)
          this.items = responseJson.response.map((item, key) =>
            this.setState({
              MDSlistdata: [...this.state.MDSlistdata, { 'Id': item.id, 'Name': item.name, 'Value': item.id }]
            })

          );

        })
        .catch(err => {
          //  console.log(err)
        })


    })
  }


  Getpatientlist() {
    //get patient//
    AsyncStorage.getItem('userdata', (err, result) => {
      let itemdata = JSON.parse(result);
      let formdata2 = new FormData();
      this.setState({
        animating: true
      })
      formdata2.append("login_session_key", itemdata.login_session_key)
      if (this.state.selectedcare) {
        formdata2.append("care_unit_id", this.state.selectedcare)

        console.log(formdata2)
        fetch('http://idcaresteward.com/api/v1/user/patient_list', {
          method: 'post',
          headers: {
            'Content-Type': 'multipart/form-data',
          },
          body: formdata2
        })
          .then((response) => response.json())
          .then((responseJson) => {
            console.log(responseJson)
            if (responseJson.status == 1) {

              this.items = responseJson.response.map((item, key) =>
                this.setState({
                  patientlistarray: [...this.state.patientlistarray, { 'Id': item.pid, 'Name': item.patient_id, 'Value': item.patient_id }]
                })

              );
              this.setState({
                animating: false
              })
            } else {
              this.setState({
                animating: false
              })

            }

          })
          .catch(err => {
            console.log(err)
          })
      }
      else {
        this.setState({
          animating: false
        })

      }
    })
    //end//
  }
  handleIndexChange = index => {
    //  alert(index)
    this.setState({
      ...this.state,
      selectedIndex: index
    });
    if (index == 0) {
      this.setState({

        selectedIndexdata: 'Hospital'
      });

    }
    else if (index == 1) {
      this.setState({

        selectedIndexdata: 'Facility'
      });
    }
  };

  handleIndexChangePatientype = index => {
    //  alert(index)
    this.setState({
      ...this.state,
      selectedIndexp_type: index
    });
    // if (index == 0) {
    //   this.setState({

    //     selectedIndexdata: 'New Patient'
    //   });

    // }
    // else if (index == 1) {
    //   this.setState({

    //     selectedIndexdata: 'Facility'
    //   });
    // }
  };
  patientmed_detail() {
    this.props.navigation.navigate('PatientcurrentDetail');
  }
  selected(selected) {
    this.setState({
      selectedItem: selected
    })
  }
  selecteddx(selected) {
    //  alert(JSON.stringify( selected))
    if (selected.Name) {
      this.setState({
        Initialdx: selected.Value
      })
      this.setState({
        Initialdxname: selected.Name
      })
    }
    else {
      this.setState({
        Initialdxname: 'Initial Dx'
      })
    }
  }
  selectedpatient(selected) {
    if (selected.Name) {
      this.setState({
        patient_unique_id: selected.Value
      })
      this.setState({
        patient_unique_placeholder: selected.Name
      })
    }
    else {
      this.setState({
        patient_unique_placeholder: 'Select Patient ID'
      })
    }

  }
  selectedcare(selected) {

    //alert( selected.length)
    if (selected.Name) {

      this.setState({
        selectedcare: selected.Value
      })
      this.setState({
        selectedcarename: selected.Name
      })
      this.Getpatientlist()
    } else {

      this.setState({
        selectedcarename: 'Search Care Unit'
      })
    }
  }
  selectedRx(selected) {
    //  alert(JSON.stringify( selected))

    if (selected.Name) {
      this.setState({
        selectedRx: selected.Value
      })
      this.setState({
        selectedRxname: selected.Name
      })
    }
    else {
      this.setState({
        selectedRxname: 'Initial Rx'
      })
    }


  }

  selecteddoctor(selected) {
    //  alert(JSON.stringify( selected))
    if (selected.Name) {
      this.setState({
        selecteddoctor: selected.Value
      })
      this.setState({
        selecteddoctorname: selected.Name
      })
    }
    else {
      this.setState({
        selecteddoctorname: 'Provider MD'
      })
    }
  }
  selectedsteward(selected) {
    //  alert(JSON.stringify( selected))
    if (selected.Name) {
      this.setState({
        selectedsteward: selected.Value
      })
      this.setState({
        selectedstewardname: selected.Name
      })
    }
    else {
      this.setState({
        selectedstewardname: 'MD Stewards'
      })
    }

  }
  selectedstewardconsult(selected) {
    //  alert(JSON.stringify( selected))
    if (selected.Name) {
      this.setState({
        selectedstewardconsult: selected.Value
      })
      this.setState({
        selectedstewardconsultname: selected.Value
      })
    }
    else {

      this.setState({
        selectedstewardconsultname: 'MD Steward Consult'
      })
    }
  }
  searchPatient() {
    this.props.navigation.navigate('Home');
    // alert(2323)
    // this.props.navigation.goBack();
  }
  goback() {
    // this.props.navigation.navigate('Home');
    // alert(2323)
    this.props.navigation.goBack();
  }

  addsavepatient() {
    //   alert('save')
    this.setState({
      animating: true
    })
    AsyncStorage.getItem('userdata', (err, result) => {
      const item = JSON.parse(result);
      const key = item.login_session_key;

      let formdata2 = new FormData();

      formdata2.append("login_session_key", key)
      // formdata2.append("name", this.state.pat_name)
      //  formdata2.append("address", this.state.pat_address)
      formdata2.append("symptom_onset", this.state.selectedIndexdata)  //Hospital,Facility
      formdata2.append("care_unit_id", this.state.selectedcare)
      formdata2.append("doctor_id", this.state.selecteddoctor)
      formdata2.append("operator_id", item.user_id)
      formdata2.append("md_steward_id", this.state.selectedsteward )

      formdata2.append("md_stayward_consult", this.state.selectedstewardconsult)
      formdata2.append("initial_rx", this.state.selectedRx)
      formdata2.append("initial_dx", this.state.Initialdx)
      formdata2.append("initial_dot", this.state.iniidotnumber)
      //  formdata2.append("md_stayward_response",'Agree')
      formdata2.append("total_day_patient_stay", parseInt(this.state.total_day_patient_stay))
      formdata2.append("patient_unique_id", this.state.patient_unique_id)
      formdata2.append("date_of_start_abx", this.state.date_of_start_abx)
      formdata2.append("pct", this.state.pct)
      // formdata2.append("infection_surveillance_checklist",'N/A')


console.log(formdata2)
      if (this.state.selectedIndexp_type == 0) {
        fetch('http://idcaresteward.com/api/v1/user/add_patient', {
          method: 'post',
          headers: {
            'Content-Type': 'multipart/form-data',
          },
          body: formdata2
        })
          .then((response) => response.json())
          .then((responseJson) => {
            console.log(responseJson)
            this.setState({
              animating: false
            })
            // alert(responseJson.message+' Please Note Patient Id:'+responseJson.response.patient_id )

            if (responseJson.status == 1) {


              // alert(responseJson.message+' Please Note Patient Id:'+responseJson.response.patient_id )
              alert('Patient added sucessfully.\n\nPlease note,Unique Id of the patient is:' + responseJson.response.patient_id_show)

              this.props.navigation.navigate('EditPentry', {
                patient_id: responseJson.response.patient_id,

              });


            }
            else {
              this.setState({
                animating: false
              })
              this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
                <Text style={{ color: 'white' }}>
                  {responseJson.message}

                </Text>
              </View>);

            }
            //  this.items = responseJson.response.map((item, key) =>
            //   this.setState({
            //  Rxlistdata: [...this.state.Rxlistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
            //    })

            //    );

          })
          .catch(err => {

            console.log(err)

            this.setState({
              animating: false
            })
            this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
              <Text style={{ color: 'white' }}>
                {responseJson.message}

              </Text>
            </View>);

          })
      }
      else if (this.state.selectedIndexp_type == 1) {
        formdata2.append("patient_id", this.state.patient_unique_id)
        console.log(formdata2)
        fetch('http://idcaresteward.com/api/v1/user/add_patient_existing ', {
          method: 'post',
          headers: {
            'Content-Type': 'multipart/form-data',
          },
          body: formdata2
        })
          .then((response) => response.json())
          .then((responseJson) => {
            console.log(responseJson)
            this.setState({
              animating: false
            })
            // alert(responseJson.message+' Please Note Patient Id:'+responseJson.response.patient_id )

            if (responseJson.status == 1) {


              // alert(responseJson.message+' Please Note Patient Id:'+responseJson.response.patient_id )
              alert('Patient added sucessfully.\n\nPlease note,Unique Id of the patient is:' + responseJson.response.patient_id_show)

              this.props.navigation.navigate('EditPentry', {
                patient_id: responseJson.response.patient_id,

              });


            }
            else {
              this.setState({
                animating: false
              })
              this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
                <Text style={{ color: 'white' }}>
                  {responseJson.message}

                </Text>
              </View>);

            }
            //  this.items = responseJson.response.map((item, key) =>
            //   this.setState({
            //  Rxlistdata: [...this.state.Rxlistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
            //    })

            //    );

          })
          .catch(err => {

            console.log(err)

            this.setState({
              animating: false
            })
            this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
              <Text style={{ color: 'white' }}>
                {responseJson.message}

              </Text>
            </View>);

          })

      }
    })

  }
  //selectComponent = (activePage) => () => this.setState({activePage})
  render() {
    console.log(this.state.selectedIndexp_type)
    return (
      <SafeAreaView style={{ flex: 1 }} >
        <Container >
          <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar>
          {/* <Header /> */}

          <Toast ref="toast" position='center' fadeOutDuration={5000} />
          <View style={{ flexDirection: 'row', justifyContent: 'space-around', marginTop: 10 }} >
            <TouchableOpacity onPress={this.goback.bind(this)} style={{ height: 30, paddingLeft: 10, width: '25%', justifyContent: 'center', alignItems: 'flex-start' }}>

              <Image style={{ width: 15, height: 15 }} source={require('./image/back.png')} ></Image>
            </TouchableOpacity>
            <View style={{ width: '50%', justifyContent: 'center', alignItems: 'center' }}>
              <Text style={{ fontWeight: 'bold', color: '#828282', fontSize: 15 }}>Register New Patient</Text>
            </View>
            <TouchableOpacity onPress={this.searchPatient.bind(this)} style={{ paddingLeft: 15, width: '25%', justifyContent: 'center', alignItems: 'center' }}>
              {/* <Icon active name='logout' /> */}
              {/* <TouchableOpacity  onPress={this.searchPatient.bind(this)} > */}
              <Image style={{ width: 15, height: 15 }} source={require('./image/home.png')} ></Image>
            </TouchableOpacity>
            {/* </View> */}

          </View>


          <ScrollView style={{ padding: 20, width: '97%', alignContent: 'center', alignSelf: 'center', marginBottom: 10 }}>
            {this.state.animating == true &&
              <ActivityIndicator
                animating={this.state.animating}
                color='#B22B57'
                size="large"
                style={styles.activityIndicator} />
            }
            {/* </View> */}

            {/* <View style={{height:45, borderWidth:0.1,  margin:3,backgroundColor:'#F7F7F7'}}>
           <Item regular style={{paddingLeft:7}}>
         
            <TextInput 
            style={{width:'100%'}}
            
           // onChangeText={FirstName => this.setState({ FirstName })}
          onSubmitEditing={() => this.pat_name.focus()}
            placeholder='Patient ID'
            placeholderTextColor='#747474' />
             </Item>
            </View> */}
            <View style={{ paddingLeft: 5, width: '100%', marginBottom: 8 }}><Text style={{ fontSize: 14, color: '#747474' }}>Patient Type</Text></View>
            <View style={{ marginBottom: 10 }}>
              <SegmentedControlTab
                values={["New Patient", "Existing"]}
                selectedIndex={this.state.selectedIndexp_type}
                onTabPress={this.handleIndexChangePatientype}
                activeTabStyle={{ backgroundColor: '#B22B57', borderColor: '#B22B57', height: 37 }}
                tabStyle={{ backgroundColor: '#F7F7F7', borderColor: '#F7F7F7', color: '#747474', height: 37, }}
                tabTextStyle={{ color: '#747474' }}
                style={{ padding: 10 }}
              //  firstTabStyle={{width:10}}
              />
            </View>

            <View style={{ height: 37, marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>

                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.selectedcarename}</Text>
                        {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>


                }
                onSelected={(selected) => this.selectedcare(selected)}
                onRequestClosed={() => this.setState({
                  selectedcarename: 'Search Care Unit'
                })}
                onBackRequest={() => this.setState({
                  selectedcarename: 'Search Care Unit'
                })}
                items={this.state.carelistdata}
                sortingLanguage={'tr'}
                showToTopButton={true}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}
                searchText={'Search...'}
                forceSelect={false}
                autoSort={true}
                backgroundColor='red'
              />



            </View>

            {this.state.selectedIndexp_type == 1 ?
              <View style={{ height: 37, marginBottom: 10 }}>
                <PickerModal
                  renderSelectView={(disabled, selected, showModal) =>
                    <View style={{
                      flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                    }}>
                      <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                        <View style={{ flexDirection: 'row', width: '95%' }}>
                          <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.patient_unique_placeholder}</Text>
                          {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                        </View>
                      </TouchableOpacity>
                      <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                        <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                      </TouchableOpacity>
                    </View>
                  }
                  onSelected={(selected) => this.selectedpatient(selected)}
                  onRequestClosed={() => console.warn('closed...')}
                  onBackRequest={() => console.warn('back key pressed')}
                  items={this.state.patientlistarray}
                  sortingLanguage={'tr'}
                  showToTopButton={true}
                  // defaultSelected={this.state.selectedItem}
                  autoCorrect={false}
                  autoGenerateAlphabet={true}
                  searchText={'Search...'}
                  forceSelect={false}
                  //  autoSort={true}
                  backgroundColor='red'
                />
              </View>
              :
              <View style={{ height: 37, marginBottom: 10 }}>

                <TextInput
                  style={{ paddingLeft: 10, width: '100%', borderWidth: 1, borderRadius: 7, height: 37, backgroundColor: '#F7F7F7', borderColor: '#F7F7F7' }}
                  onChangeText={patient_unique_id => this.setState({ patient_unique_id })}
                  //  ref={input => (this.patient_unique_id = input)}
                  placeholderTextColor='#747474'
                  maxLength={9}
                  placeholder='Patient ID' />

              </View>
            }

            {/* <View style={{ height: 37, marginBottom: 10 }}>

              <TextInput
                style={{ paddingLeft: 10, width: '100%', borderWidth: 1, borderRadius: 7, height: 37, backgroundColor: '#F7F7F7', borderColor: '#F7F7F7' }}
                onChangeText={pat_name => this.setState({ pat_name })}
                ref={input => (this.pat_address = input)}
                placeholderTextColor='#747474'
                placeholder='Patient Name' />

            </View> */}



            {/* <View style={{ height:45,borderWidth:0.1,  margin:3,backgroundColor:'#F7F7F7'}}> */}
            {/* <View style={{ height: 37, marginBottom: 10 }}>
           
              <TextInput
                style={{ paddingLeft: 10, width: '100%', borderWidth: 1, borderRadius: 7, height: 37, backgroundColor: '#F7F7F7', borderColor: '#F7F7F7' }}
                onChangeText={pat_address => this.setState({ pat_address })}
                ref={input => (this.pat_address = input)}
                placeholderTextColor='#747474'

                placeholder='Patient Address' />
         
            </View> */}



            {/* <Segment style={{backgroundColor:'#AB2E5A',width:265,alignContent:'center',alignSelf:'center',height:35}}>
           <Button  active={this.state.activePage === 1}
              onPress={this.selectComponent(1)}><Text>Component 1</Text></Button>
           <Button  active={this.state.activePage === 2}
              onPress= {this.selectComponent(2)}><Text>Component 2</Text></Button>
         </Segment> */}
            <View style={{ paddingLeft: 5, width: '100%', marginBottom: 8 }}><Text style={{ fontSize: 14, color: '#747474' }}>Infection Onset</Text></View>
            <View style={{ marginBottom: 10 }}>
              <SegmentedControlTab
                values={["Hospital", "Facility"]}
                selectedIndex={this.state.selectedIndex}
                onTabPress={this.handleIndexChange}
                activeTabStyle={{ backgroundColor: '#B22B57', borderColor: '#B22B57', height: 37 }}
                tabStyle={{ backgroundColor: '#F7F7F7', borderColor: '#F7F7F7', color: '#747474', height: 37, }}
                tabTextStyle={{ color: '#747474' }}
                style={{ padding: 10 }}
              //  firstTabStyle={{width:10}}
              />
            </View>

            <View style={{ height: 37, marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>
                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.Initialdxname}</Text>
                        {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>



                }
                onSelected={(selected) => this.selecteddx(selected)}
                onRequestClosed={() => console.warn('closed...')}
                onBackRequest={() => console.warn('back key pressed')}
                items={this.state.Dxlistdata}
                sortingLanguage={'tr'}
                showToTopButton={true}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}
                searchText={'Search...'}
                forceSelect={false}
                //  autoSort={true}
                backgroundColor='red'
              />



            </View>
            <View style={{ height: 37, marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>

                  //  <TouchableOpacity onPress={showModal}    style={{ width:'100%',
                  //  flexDirection:'row',alignItems:'center', width:'89%', backgroundColor:'#F7F7F7',height:37,borderRadius:7}}>
                  //  <View style={{width:'95%'}}>
                  //   <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selectedRxname}</Text>
                  //   {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}
                  //   </View>
                  //   <View style={{ borderTopRightRadius:7,borderBottomRightRadius:7,flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center'}}>
                  //   <Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
                  //   </View>
                  //  </TouchableOpacity>

                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.selectedRxname}</Text>
                        {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>


                }
                onSelected={(selected) => this.selectedRx(selected)}
                onRequestClosed={() => console.warn('closed...')}
                onBackRequest={() => console.warn('back key pressed')}
                items={this.state.Rxlistdata}
                sortingLanguage={'tr'}
                showToTopButton={true}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}
                searchText={'Search...'}
                forceSelect={false}
                autoSort={true}
                backgroundColor='red'
              />



            </View>

            <View style={{ borderRadius: 7, borderColor: '#F7F7F7', marginBottom: 10, flexDirection: 'row', justifyContent: 'center', backgroundColor: '#F7F7F7', height: 37 }}>
              <View style={{ width: '70%', justifyContent: 'center', paddingLeft: 10 }}>
                <Text style={{ paddingLeft: 1, color: '#757575', fontSize: 15 }}>Initial DOT</Text>
              </View>
              <View style={{ width: '30%', justifyContent: 'center' }}>
                <InputSpinner
                  // max={10}
                  editable={false}
                  min={0}
                  step={1}
                  inputStyle={{ padding: 3 }}
                  //colorMax={"#f04048"}
                  //colorMin={"#40c5f4"}
                  color={"#B22B57"}
                  width={90}
                  height={30}
                  buttonFontSize={18}
                  value={this.state.number}
                  //  buttonStyle={{width:26,height:28,paddingBotoom:8}}

                  onChange={(num) => {
                    this.setState({
                      iniidotnumber: num
                    })
                  }}
                />
              </View>
            </View>
            <View style={{ height: 37, marginBottom: 10 }}>

            <TextInput 
                style={{color:'#747474',  paddingLeft:10,width:'100%',height:'auto',minHeight:30, borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}} 
                onChangeText={pct => this.setState({ pct })}
               placeholderTextColor='#747474'
               placeholder='Enter PCT'
                />

            </View>

            {/* <TextInput 
                style={{ paddingLeft:10,width:'100%',height:'auto',minHeight:30, borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}} 
                onChangeText={pct => this.setState({ pct })}
            
             
               placeholderTextColor='#747474'
              // placeholder='Enter PCT'
                /> */}

            <View style={{ width: '100%', marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>

                  //  <TouchableOpacity onPress={showModal}    style={{ width:'100%',
                  //  flexDirection:'row',alignItems:'center', width:'89%', backgroundColor:'#F7F7F7',height:37,borderRadius:7}}>
                  //  <View style={{width:'95%'}}>
                  //   <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selecteddoctorname}</Text>
                  //   {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}
                  //   </View>
                  //   <View style={{ borderTopRightRadius:7,borderBottomRightRadius:7,flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center'}}>
                  //   <Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
                  //   </View>
                  //  </TouchableOpacity>

                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.selecteddoctorname}</Text>
                        {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>


                }
                onSelected={(selected) => this.selecteddoctor(selected)}
                onRequestClosed={() => console.warn('closed...')}
                onBackRequest={() => console.warn('back key pressed')}
                items={this.state.Doclistdata}
                sortingLanguage={'tr'}
                showToTopButton={true}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}
                searchText={'Search...'}
                forceSelect={false}
                autoSort={true}
                backgroundColor='red'
              />



            </View>


            <View style={{ width: '100%', marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>


                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.selectedstewardname}</Text>
                        {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}

                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>
                }
                onSelected={(selected) => this.selectedsteward(selected)}
                onRequestClosed={() => console.warn('closed...')}
                onBackRequest={() => console.warn('back key pressed')}
                items={this.state.MDSlistdata}
                sortingLanguage={'tr'}
                showToTopButton={true}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}
                searchText={'Search...'}
                forceSelect={false}
                autoSort={true}
                backgroundColor='red'
              />



            </View>

            <View style={{ height: 37, marginBottom: 10 }}>
              <View style={{ paddingLeft: 2, width: '100%', borderWidth: 1, borderRadius: 7, height: 37, backgroundColor: '#F7F7F7', borderColor: '#F7F7F7' }} >
                <DatePicker
                  // style={{ paddingLeft:10,width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}} 
                  //  defaultDate={new Date(2018, 4, 4)}
                  // minimumDate={new Date(2018, 1, 1)}
                  //  maximumDate={new Date(2018, 12, 31)}


                  locale={"en"}
                  timeZoneOffsetInMinutes={undefined}
                  modalTransparent={false}
                  animationType={"fade"}
                  androidMode={"default"}
                  placeHolderText="Date Of Starting ABX"
                  textStyle={{ color: "#747474" }}
                  placeHolderTextStyle={{ fontSize: 14, color: "#747474" }}
                  // onDateChange={this.setDate}
                  onDateChange={(date) => { this.setState({ date_of_start_abx: moment(date).format('YYYY-MM-DD') }) }}
                  disabled={false}
                  formatChosenDate={date => { return moment(date).format('YYYY-MM-DD'); }}

                />
              </View>

            </View>
            {/* <View style={{ height: 37, marginBottom: 10 }}>

              <TextInput
                style={{ paddingLeft: 10, width: '100%', borderWidth: 1, borderRadius: 7, height: 37, backgroundColor: '#F7F7F7', borderColor: '#F7F7F7' }}
                onChangeText={total_day_patient_stay => this.setState({ total_day_patient_stay })}
                //  ref={input => (this.pat_address = input)}
                placeholderTextColor='#747474'
                keyboardType={'numeric'}
                placeholder='Total Day Of Patient Stay' />

            </View> */}

            <View style={{ width: '100%', marginBottom: 10 }}>
              <PickerModal
                renderSelectView={(disabled, selected, showModal) =>



                  <View style={{
                    flexDirection: 'row', alignItems: 'center', width: '100%', backgroundColor: '#F7F7F7', height: 37, borderRadius: 7
                  }}>
                    <TouchableOpacity onPress={showModal} style={{ width: '60%', }}   >
                      <View style={{ flexDirection: 'row', width: '95%' }}>
                        <Text style={{ paddingLeft: 10, color: '#747474', fontSize: 14 }} >{this.state.selectedstewardconsultname}</Text>


                      </View>
                    </TouchableOpacity>
                    <TouchableOpacity onPress={showModal} style={{ borderTopRightRadius: 7, borderBottomRightRadius: 7, flexDirection: 'row', height: 37, width: '40%', backgroundColor: '#F7F7F7', justifyContent: 'flex-end', alignItems: 'center' }}>


                      <Image onPress={showModal} style={{ tintColor: "#878787", width: 12, height: 10, marginRight: 10, justifyContent: 'flex-end', marginLeft: 30 }} onTouchStart={showModal} source={require('./image/drop-down-arrow.png')} ></Image>
                    </TouchableOpacity>
                  </View>

                }
                onSelected={(selected) => this.selectedstewardconsult(selected)}
                onRequestClosed={() => console.warn('closed...')}
                onBackRequest={() => console.warn('back key pressed')}
                items={stewardcunsultlist}
                sortingLanguage={'tr'}
                showToTopButton={false}
                defaultSelected={this.state.selectedItem}
                autoCorrect={false}
                autoGenerateAlphabet={true}

                forceSelect={false}
                autoSort={true}
                backgroundColor='red'
              />



            </View>

            {/* <View style={{ backgroundColor:'red', height:40, padding:1,marginBottom:10, width:'100%',flexDirection:'row',backgroundColor:'#F7F7F7',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
      <View style={{ width:'70%' ,justifyContent:'center'}}>
        <Text style={{color:'#757575'}}>Md Steward Consult </Text>
     </View>

        <View style={{backgroundColor:'red',paddingLeft:20, width:'30%',justifyContent:'flex-end'}}>
        <Picker
  //selectedValue={this.state.language}
    style={{height: 50, width: 100}}
  // onValueChange={(itemValue, itemIndex) =>
  //   this.setState({language: itemValue})
  // }
  >
  <Picker.Item label="Yes" value="Yes" />
  <Picker.Item label="No" value="No" />
</Picker>
     </View>
    </View> */}

            <View style={{ borderRadius: 7, height: 37, marginBottom: 10, width: '100%', flexDirection: 'row', backgroundColor: '#F7F7F7', justifyContent: 'space-around', alignContent: 'center', alignItems: 'center' }}>


              <View style={{ width: '67%', justifyContent: 'center', paddingLeft: 10 }}>
                <Text style={{ color: '#757575', fontSize: 15 }}>Md Steward Response </Text>
              </View>
              <View style={{ width: '35%', justifyContent: 'center', }}>
                <Text style={{ color: '#474a7f' }}>No Response </Text>
              </View>
            </View>


            {/* <TouchableOpacity onPress={this.patientmed_detail.bind(this)}  style={{marginTop:7, marginBottom:7, borderRadius:4, backgroundColor:'#474a7f',width:'100%',height:50,alignItems:'center',justifyContent:'center'}}>
                
          <Text style={{color:'white',fontSize:18}}> MD Steward Recommandations </Text>
             
      </TouchableOpacity>  */}

            <TouchableOpacity onPress={this.addsavepatient.bind(this)} style={{ marginBottom: 20, borderRadius: 7, backgroundColor: '#8F3A5D', width: '100%', height: 43, alignItems: 'center', justifyContent: 'center' }}>

              <Text style={{ color: 'white', fontSize: 18 }}> Submit </Text>

            </TouchableOpacity>
          </ScrollView>
        </Container>

      </SafeAreaView>
    );
  }
};


const styles = StyleSheet.create({
  textInput: {
    minHeight: 28,
    fontSize: 12,
    //  fontFamily: AppFonts.HNMedium,
    backgroundColor: 'transparent',
    color: '#fff',
    width: "90%",
    letterSpacing: 0.9,
    borderRadius: 25,
    borderColor: 'transparent',
  },

  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 70
  },
  activityIndicator: {
    //  flex: 1,
    position: 'absolute',
    left: 0,
    right: 0,
    top: 0,
    bottom: 0,
    alignItems: 'center',
    justifyContent: 'center',
    //backgroundColor:'#F5FCFF',
    //  backgroundColor: 'rgba(40, 40, 40, 0.5)',
    zIndex: 9999
  }

});

