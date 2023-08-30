/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React,{Component} from 'react';
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
import Toast, {DURATION} from 'react-native-easy-toast'
import {Label,Segment,Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';
import InputSpinner from "react-native-input-spinner";

import PickerModal from 'react-native-picker-modal-view';
import SegmentedControlTab from "react-native-segmented-control-tab";
const list22 = [
	{Id: 1, Name: 'CHL', Value: 'CHL'},
	{Id: 2, Name: 'Test2 Name', Value: 'Test2 Value'},
	{Id: 3, Name: 'Test3 Name', Value: 'Test3 Value'},
	{Id: 4, Name: 'Test4 Name', Value: 'Test4 Value'}
]

const stewardcunsultlist = [
	{Id: 1, Name: 'Yes', Value: 'Yes'},
	{Id: 2, Name: 'No', Value: 'No'}

]
const CustomHeader = ({ navigation }) => (

    <View style={{ flexDirection: 'row' ,justifyContent:'center',backgroundColor:'blue'}}>
     <TouchableOpacity onPress={()=> navigation.goBack(null)} style={{flex:1,justifyContent:"flex-start"}} transparent>
     <Image
      source={require('./image/BackLight.png')}
    /> 
   
            </TouchableOpacity>
            
      <View style={{  justifyContent: 'center', paddingRight: 15 }}>
        {/* <Text>Conference</Text> */}
      </View>

    </View>

);
 
  export default class EditPentry extends Component {
   
    constructor(props) {
      super(props);
      this.state = {
        pat_name:'',
        pat_address:'',
     //   selectedIndex: 0,
        selectedItem: {},
        activePage:1,
        number:1,
        carelistdata:[],
        Dxlistdata:[],
        Rxlistdata:[],
        Doclistdata:[],
        animating: false,
        iniidotnumber:1,
        patdetaildata:'',
        date_of_start_abx:'',
        // selectedcare:'Care Unit Name ',
        // Initialdx:'Initial Dx',
        // selectedRx:'Initial Rx',
        // selecteddoctor:'Doctor',
        // selectedsteward:'MD Steward',
        // selectedstewardconsult:'MD Steward Consult',

        selectedcarename:'Care Unit Name ',
        Initialdxname:'Initial Dx',
        selectedRxname:'Initial Rx',
        selecteddoctorname:'Provider MD',
        selectedstewardname:'MD Steward',
        selectedstewardconsultname:'MD Steward Consult',
        patient_id_show:'',
        pct:''
      };
    }
    componentWillMount(){
     

   //  alert('dfdf'+this.props.navigation.getParam('patient_id'))
   //  alert('fff'+this.props.navigation.getParam('care_unit_id'))
      this.setState({
        animating: true
      })


       AsyncStorage.getItem('userdata', (err, result) => {
      

        const item = JSON.parse(result);
        const key = item.login_session_key;

        let formdata = new FormData();
       formdata.append("login_session_key",key)

       formdata.append("patient_id",this.props.navigation.getParam('patient_id'))
    
       if(this.props.navigation.getParam('care_unit_id'))
       {
       formdata.append("care_unit_id",this.props.navigation.getParam('care_unit_id'))
       }
       console.log(formdata)
       fetch('http://idcaresteward.com/api/v1/user/patient_details',{
        method: 'post',
         headers: {
        'Content-Type': 'multipart/form-data',
         },
          body: formdata
         })
      .then((response) => response.json())
      .then((responseJson) => {

console.log(responseJson)
       if(responseJson.status==1)
        {
        this.setState({ patdetaildata: responseJson.response})
        this.setState({ patient_id: responseJson.response.pid })
        this.setState({ patient_id_show: responseJson.response.patient_id })
        this.setState({ pat_name: responseJson.response.patient_name })
        this.setState({ pat_address: responseJson.response.address })
        this.setState({ selectedcarename: responseJson.response.care_unit_name })
        this.setState({ selecteddoctorname: responseJson.response.doctor_name })
        this.setState({ number: responseJson.response.initial_dot })
        this.setState({ Initialdxname: responseJson.response.initial_dx_name })
        this.setState({ selectedRxname: responseJson.response.initial_rx_name })
        this.setState({ md_stayward_consult: responseJson.response.md_stayward_consult })
        this.setState({ md_stayward_response: responseJson.response.md_stayward_response })
        this.setState({ md_steward: responseJson.response.md_steward })
        this.setState({ total_day_patient_stay: responseJson.response.total_days_of_patient_stay })
        this.setState({ symptom_onset: responseJson.response.symptom_onset })
        this.setState({ date_of_start_abx: responseJson.response.date_of_start_abx })
        this.setState({ pct: responseJson.response.pct })
        
        
        if(responseJson.response.symptom_onset=="Hospital")
        {
        
       // this.setState({ selectedIndex: 0})

        this.setState({
         
          selectedIndex: 0
        });
        }
        else if(responseJson.response.symptom_onset=="Facility")
        {
         
          this.setState({
           
            selectedIndex: 1
          });
        }
      }
      else{
        this.setState({
          animating: false
        })
        this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
        <Text style={{color:'white'}}>
       {responseJson.message}
      
        </Text>
        </View>); 
      }
   

    

     
      })
     .catch(err => {
   //  console.log(err)
     })  




    
       fetch('http://idcaresteward.com/api/v1/user/careUnit',{
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
        carelistdata: [...this.state.carelistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
         })
         
         );
     
      })
     .catch(err => {
   //  console.log(err)
     })  

     //initial Dx
     fetch('http://idcaresteward.com/api/v1/user/initialDx',{
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
     Dxlistdata: [...this.state.Dxlistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
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
       fetch('http://idcaresteward.com/api/v1/user/initialRx',{
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
       Rxlistdata: [...this.state.Rxlistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
         })
         
         );
     
      })
     .catch(err => {
   //  console.log(err)
     }) 
       //end//

        //initial Doctor
        fetch('http://idcaresteward.com/api/v1/user/doctors',{
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
         Doclistdata: [...this.state.Doclistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
           })
           
           );
       
        })
       .catch(err => {
     //  console.log(err)
       }) 
         //end//


   })
    }

    handleIndexChange = index => {
    //  alert(index)
      this.setState({
        ...this.state,
        selectedIndex: index
      });
      if(index==0)
      {
      this.setState({
     
        selectedIndexdata: 'Hospital'
      });
     
    }
    else if(index==1){
      this.setState({
       
        selectedIndexdata: 'Facility'
      });
    }
    };
    patientmed_detail(){
      this.props.navigation.navigate('PatientcurrentDetail',{patdetail:this.state.patdetaildata}); 
    }
    selected(selected) {
      this.setState({
        selectedItem: selected
      })
    }
    selecteddx(selected) {
    //  alert(JSON.stringify( selected))
      this.setState({
        Initialdx: selected.Value
      })
      this.setState({
        Initialdxname: selected.Name
      })
    }

    selectedcare(selected) {
      //  alert(JSON.stringify( selected))
        this.setState({
          selectedcare: selected.Value
        })
        this.setState({
          selectedcarename: selected.Name
        })
      }
      selectedRx(selected) {
        //  alert(JSON.stringify( selected))
          this.setState({
            selectedRx: selected.Value
          })
          this.setState({
            selectedRxname: selected.Name
          })

          
        }

        selecteddoctor(selected) {
          //  alert(JSON.stringify( selected))

         
            this.setState({
              selecteddoctor: selected.Value
            })
            this.setState({
              selecteddoctorname: selected.Name
            })
        
          }
          selectedsteward(selected) {
            //  alert(JSON.stringify( selected))
              this.setState({
                selectedsteward: selected.Value
              })
              this.setState({
                selectedstewardname: selected.Name
              })
            }
            selectedstewardconsult(selected) {
              //  alert(JSON.stringify( selected))
                this.setState({
                  selectedstewardconsult: selected.Value
                })
                this.setState({
                  selectedstewardconsultname: selected.Value
                })
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

  addsavepatient(){
 //   alert('save')
    AsyncStorage.getItem('userdata', (err, result) => {
      const item = JSON.parse(result);
      const key = item.login_session_key;

      let formdata2 = new FormData();
   
       formdata2.append("login_session_key",key)
       formdata2.append("name",this.state.pat_name)
       formdata2.append("address",this.state.pat_address)
       formdata2.append("symptom_onset",this.state.selectedIndexdata)  //Hospital,Facility
       formdata2.append("care_unit_id",this.state.selectedcare)
       formdata2.append("doctor_id",this.state.selecteddoctor)
       formdata2.append("md_steward_id", item.user_id) 
       formdata2.append("md_stayward_consult",this.state.selectedstewardconsult)
       formdata2.append("initial_rx",this.state.selectedRx)
       formdata2.append("initial_dx",this.state.Initialdx)
       formdata2.append("initial_dot",this.state.iniidotnumber)
       formdata2.append("md_stayward_response",'Agree')
       
console.log(formdata2)
    fetch('http://idcaresteward.com/api/v1/user/add_patient',{
      method: 'post',
       headers: {
      'Content-Type': 'multipart/form-data',
       },
        body: formdata2
       })
    .then((response) => response.json())
    .then((responseJson) => {
     alert(responseJson.message+' Please Note Patient Id:'+responseJson.response.patient_id )
   
    //  this.items = responseJson.response.map((item, key) =>
    //   this.setState({
    //  Rxlistdata: [...this.state.Rxlistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
    //    })
       
    //    );
   
    })
   .catch(err => {
 //  console.log(err)
     }) 
  })
  }
  //selectComponent = (activePage) => () => this.setState({activePage})
    render() {
   
  return (
    <SafeAreaView style={{flex:1}} >
    <Container >
       <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar>  
    {/* <Header /> */}
    <Toast ref="toast"  position='center' fadeOutDuration={1000}/>
    <View style={{ flexDirection:'row',justifyContent:'space-around',marginTop:10}} >
    <TouchableOpacity  onPress={this.goback.bind(this)}  style={{height:30, paddingLeft:10, width:'25%',justifyContent:'center',alignItems:'flex-start'}}>
   
    <Image style={{width:15,height:15}}  source={require('./image/back.png')} ></Image>
       </TouchableOpacity>
       <View style={{ width:'35%',justifyContent:'center',alignItems:'center'}}>
       <Text style={{fontWeight:'bold',color:'#828282',fontSize:15}}>Patient Detail</Text>
       </View>
       <TouchableOpacity  onPress={this.searchPatient.bind(this)}  style={{paddingLeft:15, width:'22%',justifyContent:'center',alignItems:'center'}}>
       {/* <Icon active name='logout' /> */}
       {/* <TouchableOpacity  onPress={this.searchPatient.bind(this)} > */}
       <Image style={{width:15,height:15}}  source={require('./image/home.png')} ></Image>
     </TouchableOpacity>
       {/* </View> */}

       </View> 
 
    
<ScrollView style={{ padding:20,   width:'97%',alignContent:'center',alignSelf:'center',marginBottom:10}}>
    {this.state.animating==true &&
            <ActivityIndicator
               animating = {this.state.animating}
               color = '#B22B57'
               size = "large"
               style = {styles.activityIndicator}/>
     }
         {/* </View> */}

      <View style={{height:37,   marginBottom:10}}>
        
          <View    style={{ paddingLeft:5,flexDirection:'row',alignItems:'center', width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7'}}><Text style={{ color:'#747474'}}>Patient's ID : </Text><Text style={{color:'#A72F5A'}}>{this.state.patient_id_show}</Text></View>
          
           
            </View> 
     
           
          <View    style={{paddingLeft:5, width:'100%',marginBottom:8}}><Text style={{ color:'#747474'}}>Infection Onset</Text></View>
          <View style={{height:37,marginBottom:10, borderWidth:0.1,backgroundColor:'#F7F7F7'}}>
         
            <SegmentedControlTab
          values={["Hospital", "Facility"]} 
          selectedIndex={this.state.selectedIndex}
         // onTabPress={this.handleIndexChange}
          activeTabStyle={{backgroundColor:'#B22B57',borderColor:'#B22B57',height:37}} 
          tabStyle={{backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',color:'#747474',height:37,}}
          tabTextStyle={{color:'#747474'}}
          style={{padding:10}}
        //  firstTabStyle={{width:10}}
        /> 

       
         </View>
    <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Care Unit Name</Text></View>
        <View style={{width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.selectedcarename}</Text></View>
        

       </View>
       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Initial Dx</Text></View>
        <View style={{width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.Initialdxname}</Text></View>
        

       </View>

       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Initial Rx</Text></View>
        <View style={{width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.selectedRxname}</Text></View>
        

       </View>

       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Initial DOT</Text></View>
        <View style={{width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.number}</Text></View>
        

       </View>

       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>PCT</Text></View>
        <View style={{ borderRadius:7,width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.pct}</Text></View>
        

       </View>

       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Provider MD</Text></View>
        <View style={{ borderRadius:7,width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.selecteddoctorname}</Text></View>
        

       </View>

       <View    style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>MD Steward</Text></View>
        <View style={{ borderRadius:7,width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.md_steward}</Text></View>
        

       </View>
       <View   style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Date Of Starting ABX</Text></View>
       <View style={{ borderRadius:7,width:'100%',marginBottom:10,height:37}}>
       
       <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.date_of_start_abx}</Text></View>
       

      </View>



       {/* <View   style={{paddingLeft:5, width:'100%',marginBottom:5}}><Text style={{ color:'#747474'}}>Total Day Patient Stay</Text></View>
        <View style={{ borderRadius:7,width:'100%',marginBottom:10,height:37}}>
       
        <View    style={{paddingLeft:7, width:'100%',borderWidth:1,borderRadius:7,height:37,backgroundColor:'#F7F7F7',borderColor:'#F7F7F7',justifyContent:'center'}}><Text style={{color:'#A72F5A'}}>{this.state.total_day_patient_stay}</Text></View>
        

       </View> */}

   
     
    
    <View style={{borderRadius:7, height:37, padding:1,marginBottom:10, width:'100%',flexDirection:'row',backgroundColor:'#F7F7F7',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
      <View style={{ paddingLeft:5,  width:'80%',justifyContent:'center'}}>
        <Text style={{color:'#757575'}}>MD Steward Consult </Text>
        </View>

        <View style={{width:'20%',justifyContent:'flex-end'}}>
      { this.state.md_stayward_consult=='Yes' &&
      
      <Text style={{color:'green'}}>{this.state.md_stayward_consult} </Text>
    }
    { this.state.md_stayward_consult=='No' &&
      
      <Text style={{color:'red'}}>{this.state.md_stayward_consult} </Text>
    }
     </View>
    </View>
        
    <View style={{borderRadius:7,height:37,marginBottom:10, width:'100%',flexDirection:'row',backgroundColor:'#F7F7F7',justifyContent:'space-around',alignContent:'center',alignItems:'center'}}>
      
    <View style={{ paddingLeft:5,width:'75%',justifyContent:'center'}}>
      <Text style={{color:'#757575',fontSize:15}}>MD Steward Response </Text>
    </View>
    
      <View style={{width:'25%',justifyContent:'flex-end',}}>
     
      { this.state.md_stayward_response=='Agree' &&
      
      <Text style={{color:'green'}}>{this.state.md_stayward_response} </Text>
     
    }
    { this.state.md_stayward_response=='Disagree' &&
      
      <Text style={{color:'red'}}>{this.state.md_stayward_response} </Text>
    }
      </View>
    </View>
          
  
     <TouchableOpacity onPress={this.patientmed_detail.bind(this)}  style={{ marginBottom:20, borderRadius:4, backgroundColor:'#474a7f',width:'100%',height:43,alignItems:'center',justifyContent:'center'}}>
                
          <Text style={{color:'white',fontSize:15}}> MD Steward Recommendations </Text>
             
      </TouchableOpacity> 
             
     {/* <TouchableOpacity  style={{ marginBottom:10, borderRadius:7,backgroundColor:'#8F3A5D',width:'100%',height:50,alignItems:'center',justifyContent:'center'}}>
                
          <Text style={{color:'white',fontSize:18}}> Submit </Text>
                 
      </TouchableOpacity>   */}

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
    borderRadius:25,
    borderColor:'transparent',
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
    //backgroundColor: 'rgba(40, 40, 40, 0.5)',
    zIndex:9999
   }
  
});

