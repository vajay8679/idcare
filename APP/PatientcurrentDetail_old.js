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
import Toast, { DURATION } from 'react-native-easy-toast'
import InputSpinner from "react-native-input-spinner";
import { Icon, Container, Header, Content, Footer, FooterTab, Button, Text, Item, Input, Form, ListItem, CheckBox, Body } from 'native-base';

import PickerModal from 'react-native-picker-modal-view';
const list22 = [
  { Id: 1, Name: 'CHL', Value: 'Test1 Value' },
  { Id: 2, Name: 'Test2 Name', Value: 'Test2 Value' },
  { Id: 3, Name: 'Test3 Name', Value: 'Test3 Value' },
  { Id: 4, Name: 'Test4 Name', Value: 'Test4 Value' }
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

export default class PatientcurrentDetail extends Component {
  // static navigationOptions = ({ navigation }) => {
  //   return {
  //     header: <CustomHeader this navigation={navigation} />
  //   };
  // };
  constructor(props) {
    super(props);
    this.state = {
      selectedItem: {},
      Dxlistdata: [],
      Rxlistdata: [],
      checked: false,
      checked2: false,
      animating: true,
      Newdxname: 'Select Dx',
      Newrxname: 'Select Rx',
      olddx: '',
      new_initial_dx_name: '',
      new_initial_rx_name: '',
      comment: '',
      selectedsurvilancename: 'Select..',
      infection_surveillance_checklist: '',
      surveillancearr: [{ Id: 1, Name: 'Yes', Value: 'Yes' },
      { Id: 2, Name: 'Not Present', Value: 'Not Present' },
      { Id: 3, Name: 'N/A', Value: 'N/A' },]
    };
  }



  loaddata() {
    AsyncStorage.getItem('userdata', (err, result) => {


      const item = JSON.parse(result);
      const key = item.login_session_key;

      const patient_id = this.props.navigation.getParam('patdetail').patient_id;
      let formdata = new FormData();
      fetch('http://idcaresteward.com/api/v1/user/patient_details', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          console.log(responseJson)

          this.setState({ new_initial_dx_name: responseJson.response.new_initial_dx_name })
          this.setState({ new_initial_rx_name: responseJson.response.new_initial_rx_name })
          this.setState({ new_initial_dot: responseJson.response.new_initial_dot })


          if (responseJson.response.md_stayward_response == 'Agree') {

            this.setState({ checked: true })

          }
          elseif(responseJson.response.md_stayward_response == 'Disagree')
          {
            this.setState({ checked2: true })
          }

          this.render();
          //  this.items = responseJson.response.map((item, key) =>
          //   this.setState({
          //   carelistdata: [...this.state.carelistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
          //    })

          //    );

        })
        .catch(err => {
          console.log(err)
        })
    })

  }
  componentWillMount() {
    this.setState({
      animating: true
    })
    this.setState({
      olddx: this.props.navigation.getParam('patdetail').new_initial_dx_name
    })

    console.log(this.props.navigation.getParam('patdetail').new_initial_dx_name)
    this.props.navigation.getParam('patdetail')
    AsyncStorage.getItem('userdata', (err, result) => {


      const item = JSON.parse(result);
      const key = item.login_session_key;
      this.setState({
        login_role: item.login_role
      })
      const patient_id = this.props.navigation.getParam('patdetail').patient_id;
      let formdata = new FormData();

      formdata.append("patient_id", patient_id)

      formdata.append("login_session_key", key)
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



      fetch('http://idcaresteward.com/api/v1/user/patient_details', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata
      })
        .then((response) => response.json())
        .then((responseJson) => {

          console.log(responseJson)

          this.setState({ new_initial_dx_name: responseJson.response.new_initial_dx_name })
          this.setState({ new_initial_rx_name: responseJson.response.new_initial_rx_name })
          this.setState({ new_initial_dot: responseJson.response.new_initial_dot })
          this.setState({ symptom_onset: responseJson.response.symptom_onset })
          this.setState({ infection_surveillance_checklist: responseJson.response.infection_surveillance_checklist })
          this.setState({ comment: responseJson.response.comment })

          if (responseJson.response.md_stayward_response == 'Agree') {

            this.setState({ checked: true })

          }
          else if (responseJson.response.md_stayward_response == 'Disagree') {
            this.setState({ checked2: true })
          }

          //  this.items = responseJson.response.map((item, key) =>
          //   this.setState({
          //   carelistdata: [...this.state.carelistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
          //    })

          //    );

        })
        .catch(err => {
          console.log(err)
        })

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
    })
  }
  updatenewDOT() {

    //   alert('save')
    AsyncStorage.getItem('userdata', (err, result) => {
      const item = JSON.parse(result);
      const key = item.login_session_key;

      let formdata2 = new FormData();

      formdata2.append("login_session_key", key)
      formdata2.append("patient_id", this.props.navigation.getParam('patdetail').patient_id)

      formdata2.append("new_initial_rx", this.state.NewdRx)
      formdata2.append("new_initial_dx", this.state.Newdx)
      formdata2.append("new_initial_dot", this.state.iniidotnumber)
      formdata2.append("comment", this.state.comment)

      if (this.state.checked == true) {
        formdata2.append("md_stayward_response", 'Agree')
      }
      else if (this.state.checked2 == true) {
        formdata2.append("md_stayward_response", 'Disagree')
      }
      formdata2.append("infection_surveillance_checklist", this.state.selectedsurvilanceval)


      console.log(formdata2)
      fetch('http://idcaresteward.com/api/v1/user/update_patient', {
        method: 'post',
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        body: formdata2
      })
        .then((response) => response.json())
        .then((responseJson) => {

          console.log(responseJson)
       if(responseJson.status==1 )
         {
          //  alert(responseJson.message)
          this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
            <Text style={{ color: 'white' }}>
              {responseJson.message}

            </Text>
          </View>);
        }
       else{
        this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
        <Text style={{ color: 'white' }}>
          {responseJson.message}

        </Text>
      </View>);
        }
         

        })
        .catch(err => {
          // console.log(err)
          // alert(responseJson.message)
          this.refs.toast.show(<View style={{ justifyContent: "center", alignItems: "center" }}>
            <Text style={{ color: 'white' }}>
              {responseJson.message}

            </Text>
          </View>);
        })
      this.props.navigation.navigate('Home');
      //  this.loaddata()
    })

  }
  selected(selected) {
    this.setState({
      selectedItem: selected
    })
  }
  searchPatient() {
    this.props.navigation.navigate('Patientlist');
    // alert(2323)
  }
  // addnewPatient()
  // {
  //   this.props.navigation.navigate('Newentry'); 
  // }
  goback() {
    // this.props.navigation.navigate('Home');
    // alert(2323)
    this.props.navigation.goBack();
  }

  selecteddx(selected) {
    //  alert(JSON.stringify( selected))
    // this.setState({
    //   Newdx: selected.Value
    // })

    if (selected.Name) {
      this.setState({
        Newdx: selected.Value
      })
      this.setState({
        Newdxname: selected.Name
      })
    }
    else {
      this.setState({
        Newdxname: 'Select Dx'
      })
    }

  }


  selectedRx(selected) {
    //  alert(JSON.stringify( selected))
    // this.setState({
    //   NewdRx: selected.Value
    // })
    if (selected.Name) {
      this.setState({
        NewdRx: selected.Value
      })
      this.setState({
        Newrxname: selected.Name
      })
    }
    else {
      this.setState({
        Newrxname: 'Select Rx'
      })
    }


  }



  selectedsurvilance(selected) {
    //  alert(JSON.stringify( selected))
    // this.setState({
    //   NewdRx: selected.Value
    // })
    if (selected.Name) {
      this.setState({
        selectedsurvilanceval: selected.Value
      })
      this.setState({
        selectedsurvilancename: selected.Name
      })
    }
    else {
      this.setState({
        selectedsurvilancename: 'Select..'
      })
    }


  }
  handleclick1() {
    this.setState({ checked: true })
    this.setState({ checked2: false })
  }
  handleclick2() {
    this.setState({ checked: false })
    this.setState({ checked2: true })
  }

  render() {
    const { navigation } = this.props;

    //alert(566)

    return (

      <Container >
        <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar>
        {/* <Header /> */}
        <Toast style={{ width: '90%' }} position='center' ref="toast" fadeOutDuration={6000} />
        <View style={{ flexDirection: 'row', justifyContent: 'space-around', marginTop: 10 }} >
          <TouchableOpacity onPress={this.goback.bind(this)} style={{ paddingLeft: 10, width: '20%', justifyContent: 'center', alignItems: 'flex-start' }}>

            <Image style={{ width: 15, height: 15 }} source={require('./image/back.png')} ></Image>
          </TouchableOpacity>
          <View style={{ width: '50%', justifyContent: 'center', alignItems: 'center' }}>
            <Text style={{ fontWeight: 'bold', color: '#747474', fontSize: 16 }}>Patient Current Details</Text>
          </View>
          <TouchableOpacity style={{ marginTop: 3, backgroundColor: '#474a7f', marginLeft: 15, borderRadius: 5, width: 20,height:20, justifyContent: 'center', alignItems: 'center' }}>
            {/* <Icon active name='logout' /> */}
            {/* <TouchableOpacity  onPress={this.searchPatient.bind(this)} > */}
            {/* <Image style={{width:22,height:22}}  source={require('./image/H.png')} ></Image> */}

            {this.state.symptom_onset == "Facility" &&
              <Text style={{ color: 'white' }}>F</Text>
            }
            {this.state.symptom_onset == "Hospital" &&
              <Text style={{ color: 'white' }}>H</Text>
            }
          </TouchableOpacity>
          {/* </View> */}

        </View>

        {/* <View style={{backgroundColor:'#B22B57'}}> */}
        <View style={{ backgroundColor: '#B22B57', flexDirection: 'column', flex: 1 }}>
        {this.state.animating == true &&
                <ActivityIndicator
                  animating={this.state.animating}
                  color='#B22B57'
                  size="large"
                  style={styles.activityIndicator} />
              }
          <ScrollView style={{ backgroundColor:'white', borderBottomLeftRadius:20,borderBottomRightRadius:20,backgroundColor:'white' }}>
            
       
          <View style={{ padding:16, alignItems:'flex-start',alignContent:'flex-start',marginTop:15}} >
        <View style={{flexDirection:'row'}}>
        <Text  style={{textAlign:'left',paddingLeft:21,fontSize:17,color:'#747474'}}>Patient's ID : </Text>
  <Text  style={{textAlign:'left',paddingLeft:3,fontSize:17,color:'#474a7f'}}>{navigation.getParam('patdetail').patient_id}</Text>
        </View>
    
        <View style={{flexDirection:'row'}}>
        <Text  style={{textAlign:'left',paddingLeft:20,fontSize:16,color:'#747474'}}>Care Unit Name : </Text>
        <Text  style={{textAlign:'left',paddingLeft:3,fontSize:16,color:'#B22B57'}}>{navigation.getParam('patdetail').care_unit_name}</Text>
        </View>
       
      <View style={{height:36,  borderRadius:7,width:'100%',backgroundColor:'#EFF7FA',marginTop:15}}>
         <View style={{ borderRadius:7, height:36, padding:5,marginTop:7, width:'100%',flexDirection:'row',backgroundColor:'#EFF7FA',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
         <View style={{width:'30%'}}>
         <Text style={{color:'#747474',fontSize:16}}>Initial Dx</Text>
      </View>
      <View style={{ width:'50%'}}>
        <Text style={{color:'#B22B57',fontSize:16}}>{navigation.getParam('patdetail').initial_dx_name}</Text>
        </View>
        </View>

       </View>
     <View style={{ height:36, borderRadius:7,width:'100%',backgroundColor:'#EFF7FA',marginTop:15}}>
         <View style={{borderRadius:7,height:36, padding:10,marginTop:7, width:'100%',flexDirection:'row',backgroundColor:'#EFF7FA',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
         <View style={{width:'30%'}}>
      <Text style={{color:'#747474',fontSize:16}}>Initial Rx</Text>
      </View>
      <View style={{ width:'50%'}}>
      <Text style={{color:'#B22B57',fontSize:16}}>{navigation.getParam('patdetail').initial_rx_name} </Text>
      </View>
    </View>

     </View>
     <View style={{ height:36, borderRadius:7,width:'100%',backgroundColor:'#EFF7FA',marginTop:15}}>
         <View style={{ borderRadius:7,height:36, padding:5,marginTop:7, width:'100%',flexDirection:'row',backgroundColor:'#EFF7FA',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
         <View style={{width:'30%'}}>
      <Text style={{color:'#747474',fontSize:16}}>Provider MD</Text>
      </View>
      <View style={{ width:'50%'}}>
      <Text style={{color:'#B22B57',fontSize:16}}>{navigation.getParam('patdetail').doctor_name}</Text>
      </View>
    </View>
    

     </View>

     <View style={{height:36,  borderRadius:7, width:'100%',backgroundColor:'#EFF7FA',marginTop:15}}>
         <View style={{ borderRadius:7,height:36, padding:5,marginTop:7, width:'100%',flexDirection:'row',backgroundColor:'#EFF7FA',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
         <View style={{width:'30%'}}>
        <Text style={{color:'#747474',fontSize:16}}>Initial Dot</Text>
        </View>
        <View style={{ width:'50%'}}>
         <Text style={{color:'#B22B57',fontSize:16}}>{navigation.getParam('patdetail').initial_dot}</Text>
         </View>
       </View>
       
     </View>

     <View style={{ width:'100%',marginTop:5,marginLeft:25,marginRight:25}}>
         <ListItem  noBorder style={{borderWidth:0}}>
            <CheckBox onPress={() => this.handleclick1()}   checked={this.state.checked} color='#614375' backgroundColor='#614375' />
            <Body>
              <Text>Agree</Text>
            </Body>
            <CheckBox onPress={() =>this.handleclick2()}  checked={this.state.checked2}   checkedColor='red' color='#614375' backgroundColor='#C6C6C6' />
            <Body>
              <Text>Disagree</Text>
            </Body>
          </ListItem>
        
          </View>

           
         </View>
      </ScrollView>

           {/* second scroll */}
       <ScrollView>
          
            <View style={{flexDirection:'row',justifyContent:'space-around',alignItems:'center'}}>
            <View style={{  justifyContent:"center",marginTop:10}}>
             <Text style={{color:'white',fontWeight:'bold'}}>MD Steward Recommendation</Text>
           
           </View>
           
           </View>
           { this.state.new_initial_dx_name==null&& this.state.new_initial_rx_name==null&& this.state.login_role=='Md Steward'  ?
          
       
          
          
            <View style={{flex:1}}>
           <View style={{ flexDirection:'row',height:50}}>
            <View style={{alignContent:'center',justifyContent:'center',alignItems:'center', flex:1}}>
            <Text style={{ color:'white',fontWeight:'bold'}}>New Dx </Text>
            </View>
            <View style={{ flex:2,justifyContent:'center'}}>
            <PickerModal

renderSelectView={(disabled, selected, showModal) =>

<TouchableOpacity onPress={showModal}  
style={{ height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
<View style={{width:'80%'}}>
<Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.Newdxname} </Text>

</View>
<View style={{ flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center',borderRadius:7}}>
<Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
</View>
</TouchableOpacity>
}
onSelected={(selected) => this.selecteddx(selected)}
 onRequestClosed={()=> console.warn('closed...')}
 onBackRequest={()=> console.warn('back key pressed')}
 items={this.state.Dxlistdata}
sortingLanguage={'tr'}
//	showToTopButton={true}
//	defaultSelected={'dsd'}
autoCorrect={false}
autoGenerateAlphabet={true}
chooseText={'Select Care Unit'}
searchText={'Search...'} 
forceSelect={false}
autoSort={true}
selectPlaceholderText={'Select DX '}
/>
             </View>

            </View>

            {/* second */}

            <View style={{ flexDirection:'row',height:50}}>
            <View style={{alignContent:'center',justifyContent:'center',alignItems:'center', flex:1}}>
            <Text style={{ color:'white',fontWeight:'bold'}}>New Rx </Text>
            </View>
            <View style={{ flex:2,justifyContent:'center'}}>
            <PickerModal
          renderSelectView={(disabled, selected, showModal) =>
 
            <TouchableOpacity onPress={showModal}  
            style={{height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
             <View style={{width:'80%'}}>
            <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selectedsurvilancename} </Text>
       
           </View>
           <View style={{ flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center',borderRadius:8}}>
            <Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
           </View>
           </TouchableOpacity>
      
      
        }
       	onSelected={(selected) => this.selectedsurvilance(selected)}
        onRequestClosed={()=> console.warn('closed...')}
        onBackRequest={()=> console.warn('back key pressed')}
      	items={this.state.surveillancearr}
       sortingLanguage={'tr'}
       showToTopButton={true}
       defaultSelected={'dsd'}
       autoCorrect={false}
       autoGenerateAlphabet={true}
       chooseText={'Select'}
       selectPlaceholderText={'Select Rx '}
    
       searchText={'Search...'} 
       forceSelect={false}
       autoSort={true}
      
  
   />
             </View>

            </View>

            {/* third */}
            <View style={{ flexDirection:'row',height:50}}>

            <View style={{alignContent:'center',justifyContent:'center',alignItems:'center', flex:1}}>
            <Text style={{ color:'white',fontWeight:'bold'}}>New Dot </Text>
            </View>
            <View style={{ flex:2,justifyContent:'center',backgroundColor:'white',alignItems:'flex-end'}}>
            <InputSpinner
         // max={10}
         editable={false}
          min={1}
         step={1}
         //colorMax={"#f04048"}
         //colorMin={"#40c5f4"}
          color={"#B22B57"}
         width={120}
         height={35}
         value={this.state.number}
       //  buttonStyle={{width:25,height:25,}}
       //  style={{padding:5,borderRadius:7,marginTop:7}}

          onChange={(num) => {
           this.setState({
            iniidotnumber: num
          })
         }}
         /> 
   
             </View>
             </View>



  <View style={{ flexDirection:'row',height:50}}>

<View style={{ alignContent:'center',justifyContent:'center',alignItems:'flex-start',flex:1,marginLeft:'11%'}}>
<Text style={{ color:'white',fontWeight:'bold'}}>Infection surveillance checklist </Text>
</View>


 </View>


   
     <View style={{ flexDirection:'row',height:50}}>

     <View style={{alignContent:'center',justifyContent:'center',alignItems:'flex-start'}}>
     <Text style={{paddingLeft:25, color:'white',fontWeight:'bold'}}>Infection surveillance checklist </Text>
     </View>
     </View>
     <View style={{ flexDirection:'row',height:50}}>
     <View style={{alignContent:'center',justifyContent:'center',alignItems:'flex-start',flex:1}}>
   
     </View>
     <View style={{alignContent:'center',justifyContent:'center',alignItems:'center',flex:2}}>
 
     
     <PickerModal
          renderSelectView={(disabled, selected, showModal) =>
 
            <TouchableOpacity onPress={showModal}  
            style={{height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
             <View style={{width:'80%'}}>
            <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selectedsurvilancename} </Text>
       
           </View>
           <View style={{ flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center',borderRadius:8}}>
            <Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
           </View>
           </TouchableOpacity>
      
      
        }
       	onSelected={(selected) => this.selectedsurvilance(selected)}
        onRequestClosed={()=> console.warn('closed...')}
        onBackRequest={()=> console.warn('back key pressed')}
      	items={this.state.surveillancearr}
       sortingLanguage={'tr'}
       showToTopButton={true}
       defaultSelected={'dsd'}
       autoCorrect={false}
       autoGenerateAlphabet={true}
       chooseText={'Select'}
       selectPlaceholderText={'Select Rx '}
    
       searchText={'Search...'} 
       forceSelect={false}
       autoSort={true}
      
  
   />
     </View>
    </View>

       

      <View style={{ flexDirection:'row',height:50}}>

     <View style={{alignContent:'center',justifyContent:'center',alignItems:'flex-start'}}>
     <Text style={{paddingLeft:25, color:'white',fontWeight:'bold'}}>Additional Comment </Text>
     </View>
     </View>

     <View style={{ flexDirection:'row',height:50}}>
     <View style={{alignContent:'center',justifyContent:'center',alignItems:'flex-start',flex:1}}>
   
     </View>
     <View style={{alignContent:'center',justifyContent:'center',alignItems:'center',flex:2}}>
 <TextInput 
                style={{ paddingLeft:10,width:'90%',height:'auto',minHeight:50, borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}} 
                onChangeText={comment => this.setState({ comment })}
             
               placeholderTextColor='#747474'
               placeholder='Comment' />
         
     </View>
     </View>



     <View style={{marginBottom:5, flexDirection:'row',justifyContent:'flex-start',alignItems:'flex-start'}}>
            <View style={{ flexDirection:'row', justifyContent:"flex-start",marginTop:3,paddingLeft:50}}>
             <Text style={{color:'white',fontWeight:'bold'}}>Additional Comment</Text>
           
           </View>
           
        </View>



           

<View style={{marginTop:12,marginBottom:15, paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
         <View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>
         
     
   {/* <Text style={{  color:'white',fontWeight:'bold'}}>Additional Comment</Text> */}
         
         </View>
         
         <View style={{marginBottom:5, height:37,paddingRight:26,justifyContent:"center",margin:6,borderRadius:7}}>
         
         <View   
         style={{ height:'auto',minHeight:50, flexDirection:'row',paddingRight:26,alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
         <View style={{ height:'auto',minHeight:50, width:'100%',paddingTop:3,paddingBottom:3}}>
         <TextInput 
                style={{ paddingLeft:10,width:'90%',borderWidth:1,paddingRight:26,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}} 
                onChangeText={comment => this.setState({ comment })}
             
               placeholderTextColor='#747474'
               placeholder='Enter Comment' />
         
         </View>
         
         </View>
         
         </View>
         
         
         </View>

     <TouchableOpacity onPress={this.updatenewDOT.bind(this)} style={{marginLeft:20, width:'100%',alignSelf:'center', margin:10, marginTop:10,marginBottom:20, borderRadius:7,backgroundColor:'white',height:43,alignItems:'center',justifyContent:'center'}}>
                
                <Text style={{color:'#8F3A5D',fontSize:18}}> Submit </Text>
                       
          </TouchableOpacity> 
          </View>

         :
         <View style={{flex:1,marginBottom:5}}>
     <View style={{paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
         <View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>
       
          <Text style={{  color:'white',fontWeight:'bold'}}>New Dx </Text>
        
        </View>

        <View style={{ height:37,backgroundColor:'white', width:'60%', justifyContent:"center",margin:6,borderRadius:7}}>

       <View   
        style={{ height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
       <View style={{width:'80%'}}>
        <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.new_initial_dx_name} </Text>

       </View>

        </View>

        </View>

        
      </View>

<View style={{paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
<View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>

 <Text style={{  color:'white',fontWeight:'bold'}}>New Rx </Text>

</View>

<View style={{ height:37,backgroundColor:'white', width:'60%', justifyContent:"center",margin:6,borderRadius:7}}>

<View   
style={{ height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
<View style={{width:'80%'}}>
<Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.new_initial_rx_name} </Text>

</View>

</View>

</View>


</View>

<View style={{paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
<View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>

 <Text style={{  color:'white',fontWeight:'bold'}}>New Dot1 </Text>

</View>

<View style={{ height:37,backgroundColor:'white', width:'60%', justifyContent:"center",margin:6,borderRadius:7}}>

<View   
style={{ height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
<View style={{width:'80%'}}>
<Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.new_initial_dot} </Text>

</View>

</View>

</View>


</View>

    <View style={{flexDirection:'row'}}>
            <View style={{ flexDirection:'row', justifyContent:"flex-start",marginTop:8,paddingLeft:45}}>
             <Text style={{color:'white',fontWeight:'bold'}}>Infection surveillance checklist</Text>
           
           </View>
           
    </View>
    
    <View style={{paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
         <View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>
         
        
         
         </View>
         
         <View style={{ height:37,backgroundColor:'white', width:'60%', justifyContent:"center",margin:6,borderRadius:7}}>
         
         <View   
         style={{ height:37, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
         <View style={{width:'80%'}}>
         <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.infection_surveillance_checklist} </Text>
         
         </View>
         
         </View>
         
         </View>
         
         
     </View>
         <View style={{marginBottom:5, flexDirection:'row',justifyContent:'flex-start',alignItems:'flex-start'}}>
            <View style={{ flexDirection:'row', justifyContent:"flex-start",marginTop:3,paddingLeft:45}}>
             <Text style={{color:'white',fontWeight:'bold'}}>Additional Comment</Text>
           
           </View>
           
        </View>
         <View style={{marginTop:12,marginBottom:15, paddingLeft:15,paddingRight:15, flexDirection:'row',justifyContent:'space-around'}}>
         
         <View style={{  height:37,flexDirection:'row', width:'40%', margin:5, justifyContent:"space-around",alignItems:'center'}}>
         
     
   {/* <Text style={{  color:'white',fontWeight:'bold'}}>Additional Comment</Text> */}
         
         </View>
         
         <View style={{marginBottom:5, height:37,backgroundColor:'white', width:'60%', justifyContent:"center",margin:6,borderRadius:7}}>
         
         <View   
         style={{ height:'auto',minHeight:50, flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',borderRadius:7}}>
         <View style={{ height:'auto',minHeight:50, width:'80%',paddingTop:3,paddingBottom:3}}>
         <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.comment} </Text>
         
         </View>
         
         </View>
         
         </View>
         
         
         </View>




</View>

        
         
    
 
    
    

 
     
     

        }
           
            
          </ScrollView>
        </View>
     

      </Container>


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
    //backgroundColor: 'rgba(40, 40, 40, 0.5)',
    zIndex: 9999
  }

});

